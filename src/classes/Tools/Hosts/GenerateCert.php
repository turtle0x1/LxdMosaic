<?php

namespace dhope0000\LXDClient\Tools\Hosts;

use GuzzleHttp\Client as GuzzleClient;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use dhope0000\LXDClient\Model\Client\LxdClient;

class GenerateCert
{
    private $certSettings = [
        "countryName"            => "UK",
        "stateOrProvinceName"    => "Isle Of Wight",
        "localityName"           => "Cowes",
        "organizationName"       => "Open Sauce Systems",
        "organizationalUnitName" => "Dev",
        "commonName"             => "127.0.0.1",
        "emailAddress"           => "info@opensauce.systems"
    ];

    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function createCertAndPushToServer(
        $urlAndPort,
        $trustPassword
    ) {
        $paths = $this->createCertKeyAndCombinedPaths($urlAndPort);

        $this->generateCert($paths);

        $lxdResponse = $this->addToServer($urlAndPort, $trustPassword, $paths["combined"]);

        $shortPaths = $this->createShortPaths($paths);

        return [
            "shortPaths"=>$shortPaths,
            "lxdResponse"=>$lxdResponse
        ];
    }

    private function createCertKeyAndCombinedPaths($urlAndPort)
    {
        $host = parse_url($urlAndPort)["host"];

        return [
            "key"=>$_ENV["LXD_CERTS_DIR"] . $host . ".key",
            "cert"=>$_ENV["LXD_CERTS_DIR"] . $host . ".cert",
            "combined"=>$_ENV["LXD_CERTS_DIR"] . $host . ".combined"
        ];
    }

    private function createShortPaths($pathsArray)
    {
        foreach ($pathsArray as $key => $path) {
            $pathsArray[$key] = str_replace($_ENV["LXD_CERTS_DIR"], "", $path);
        }
        return $pathsArray;
    }

    private function addToServer($urlAndPort, $trustPassword, $pathToCert)
    {
        $config = $this->lxdClient->createConfigArray($pathToCert);
        $config["timeout"] = 2;
        $lxd = $this->lxdClient->createNewClient($urlAndPort, $config);
        return $lxd->certificates->add(file_get_contents($pathToCert), $trustPassword);
    }

    private function generateCert($paths)
    {
        // Generate certificate
        $privkey = openssl_pkey_new();
        $cert    = openssl_csr_new($this->certSettings, $privkey);
        $cert    = openssl_csr_sign($cert, null, $privkey, 365);

        // Generate strings
        openssl_x509_export($cert, $certString);
        openssl_pkey_export($privkey, $privkeyString);

        if (file_put_contents($paths["key"], $privkeyString) != true) {
            throw new \Exception("Couldn't store key file", 1);
        }

        if (file_put_contents($paths["cert"], $certString) != true) {
            throw new \Exception("Couldn't store cert file", 1);
        }

        if (file_put_contents($paths["combined"], $certString.$privkeyString) != true) {
            throw new \Exception("Couldn't store combined file", 1);
        }

        return true;
    }
}

<?php

namespace dhope0000\LXDClient\Tools\Hosts;

use GuzzleHttp\Client as GuzzleClient;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use dhope0000\LXDClient\Model\Client\LxdClient;

class GenerateCert
{
    private LxdClient $lxdClient;

    private array $certSettings = [
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
        string $urlAndPort,
        string $trustPassword,
        ?string $socketPath = null
    ) :array {
        $paths = $this->createCertKeyAndCombinedPaths($urlAndPort, $socketPath);

        $this->generateCert($paths);

        $lxdResponse = $this->addToServer($urlAndPort, $trustPassword, $paths["combined"], $socketPath);

        $shortPaths = $this->createShortPaths($paths);

        return [
            "shortPaths"=>$shortPaths,
            "lxdResponse"=>$lxdResponse
        ];
    }

    private function createCertKeyAndCombinedPaths(string $urlAndPort, ?string $socketPath) :array
    {
        if (!empty($socketPath)) {
            $host = $urlAndPort;
        } else {
            $host = parse_url($urlAndPort);

            if ($host == false) {
                throw new \Exception("Couldn't parse '$urlAndPort'", 1);
            }


            $host = $host["host"];
        }


        return [
            "key"=>$_ENV["LXD_CERTS_DIR"] . $host . ".key",
            "cert"=>$_ENV["LXD_CERTS_DIR"] . $host . ".cert",
            "combined"=>$_ENV["LXD_CERTS_DIR"] . $host . ".combined"
        ];
    }

    private function createShortPaths(array $pathsArray) :array
    {
        foreach ($pathsArray as $key => $path) {
            $pathsArray[$key] = str_replace($_ENV["LXD_CERTS_DIR"], "", $path);
        }
        return $pathsArray;
    }

    private function addToServer(string $urlAndPort, string $trustPassword, string $pathToCert, ?string $socketPath)
    {
        $config = $this->lxdClient->createConfigArray($pathToCert, $socketPath);
        $config["timeout"] = 2;
        $lxd = $this->lxdClient->createNewClient($urlAndPort, $config);
        return $lxd->certificates->add(file_get_contents($pathToCert), $trustPassword);
    }

    private function generateCert(array $paths) :bool
    {
        // Generate certificate
        $privkey = openssl_pkey_new();
        $cert    = openssl_csr_new($this->certSettings, $privkey);

        if ($cert == false) {
            throw new \Exception("Couldn't generate new certificate", 1);
        }

        $cert    = openssl_csr_sign($cert, null, $privkey, 365);

        if ($cert == false) {
            throw new \Exception("Couldn't sign new certificate", 1);
        }

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

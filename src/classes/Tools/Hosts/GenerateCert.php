<?php

namespace dhope0000\LXDClient\Tools\Hosts;

use dhope0000\LXDClient\Model\Client\LxdClient;

class GenerateCert
{
    private $lxdClient;

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
        $trustPassword = null,
        $socketPath = null,
        $token = null
    ) {
        $paths = $this->createCertKeyAndCombinedPaths($urlAndPort, $socketPath);

        $this->generateCert($paths);

        $lxdResponse = $this->addToServer($urlAndPort, $trustPassword, $token, $paths["combined"], $socketPath);

        $shortPaths = $this->createShortPaths($paths);

        return [
            "shortPaths"=>$shortPaths,
            "lxdResponse"=>$lxdResponse
        ];
    }

    private function createCertKeyAndCombinedPaths($urlAndPort, $socketPath)
    {
        if (!empty($socketPath)) {
            $host = $urlAndPort;
        } else {
            $host = parse_url($urlAndPort)["host"];
        }


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

    private function addToServer($urlAndPort, $trustPassword, $token, $pathToCert, $socketPath)
    {
        $config = $this->lxdClient->createConfigArray($pathToCert, $socketPath);
        $config["timeout"] = 2;
        $lxd = $this->lxdClient->createNewClient($urlAndPort, $config);

        $clientName = null;
        if($token !== null){
            if(base64_decode($token) == false || json_decode(base64_decode($token), true) == null){
                throw new \Exception("'$urlAndPort' Token is not valid");
            }
            $clientName = json_decode(base64_decode($token), true)["client_name"];
        }

        return $lxd->certificates->add(file_get_contents($pathToCert), $trustPassword, $clientName, $token);
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

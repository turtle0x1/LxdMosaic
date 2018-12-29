<?php

namespace dhope0000\LXDClient\Tools\Hosts;

use GuzzleHttp\Client as GuzzleClient;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use dhope0000\LXDClient\Constants\Constants;
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
        $trustPassword,
        $pathToCert = null
    ) {
        if (is_null($pathToCert)) {
            $pathToCert = $this->generateCertPathName($urlAndPort);
            $this->generateCert($pathToCert);
        }

        return $this->addToServer($urlAndPort, $trustPassword, $pathToCert);
    }

    public function generateCertPathName($urlAndPort)
    {
        return Constants::CERTS_DIR . $this->generateName($urlAndPort);
    }

    public function generateName($urlAndPort)
    {
        return parse_url($urlAndPort)["host"] . ".cert";
    }

    private function addToServer($urlAndPort, $trustPassword, $pathToCert)
    {
        $config = $this->lxdClient->createConfigArray($pathToCert);
        $lxd = $this->lxdClient->createNewClient($urlAndPort, $config);
        return $lxd->certificates->add(file_get_contents($pathToCert), $trustPassword);
    }

    private function generateCert($pathToCert)
    {
        // Generate certificate
        $privkey = openssl_pkey_new();
        $cert    = openssl_csr_new($this->certSettings, $privkey);
        $cert    = openssl_csr_sign($cert, null, $privkey, 365);

        // Generate strings
        openssl_x509_export($cert, $certString);
        openssl_pkey_export($privkey, $privkeyString);
        if (file_put_contents($pathToCert, $certString.$privkeyString) != true) {
            throw new \Exception("Couldn't save cert", 1);
        }
    }
}

<?php
namespace dhope0000\LXDClient\Model\Client;

use GuzzleHttp\Client as GuzzleClient;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use \Opensaucesystems\Lxd\Client;
use dhope0000\LXDClient\Constants\Constants;
use dhope0000\LXDClient\Model\Hosts\GetDetails;
use Symfony\Component\HttpFoundation\Session\Session;

class LxdClient
{
    private $clientBag = [];

    public function __construct(GetDetails $getDetails, Session $session)
    {
        $this->getDetails = $getDetails;
        $this->session = $session;
    }

    public function getANewClient($hostId, $checkCache = true, $setProject = true)
    {
        $hostDetails = $this->getDetails->getAll($hostId);

        if (empty($hostDetails)) {
            throw new \Exception("Couldn't find info for this host", 1);
        }

        if ($checkCache && isset($this->clientBag[$hostDetails["Host_Url_And_Port"]])) {
            return $this->clientBag[$hostDetails["Host_Url_And_Port"]];
        }

        $certPath = $this->createFullcertPath($hostDetails["Host_Cert_Path"]);
        $config = $this->createConfigArray($certPath);
        $client = $this->createNewClient($hostDetails["Host_Url_And_Port"], $config);
        
        if ($setProject) {
            $client->setProject($this->session->get("host/$hostId/project", "default"));
        }

        return $client;
    }

    private function createFullcertPath(string $certName)
    {
        return Constants::CERTS_DIR . $certName;
    }

    public function createConfigArray($certLocation)
    {
        $certPath = realpath($certLocation);

        if ($certPath === false) {
            throw new \Exception("Certificate has gone walk abouts", 1);
        }

        $config = [
            'verify' => false,
            'cert' => [
                $certPath,
                ''
            ]
        ];

        if (file_exists("/etc/centos-release")) {
            $config["headers"] = [
                'Connection' => 'close'
            ];
        }

        return $config;
    }

    public function createNewClient($urlAndPort, $config)
    {
        $guzzle = new GuzzleClient($config);
        $adapter = new GuzzleAdapter($guzzle);
        $client = new Client($adapter, null, $urlAndPort);
        $this->clientBag[$urlAndPort] = $client;
        return $client;
    }
}

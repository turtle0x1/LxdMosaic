<?php
namespace dhope0000\LXDClient\Model\Client;

use GuzzleHttp\Client as GuzzleClient;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use \Opensaucesystems\Lxd\Client;
use dhope0000\LXDClient\Model\Users\Projects\FetchUserProject;
use dhope0000\LXDClient\App\RouteApi;
use dhope0000\LXDClient\Objects\Host;

class LxdClient
{
    private $clientBag = [];

    public function __construct(
        FetchUserProject $fetchUserProject,
        RouteApi $routeApi
    ) {
        $this->fetchUserProject = $fetchUserProject;
        $this->routeApi = $routeApi;
    }

    public function getClientWithHost(Host $host, $checkCache = true, $setProject = true)
    {
        if ($checkCache && isset($this->clientBag[$host->getUrl()])) {
            return $this->clientBag[$host->getUrl()];
        }

        $certPath = $this->createFullcertPath($host->getCertPath());
        $config = $this->createConfigArray($certPath);
        $client = $this->createNewClient($host->getUrl(), $config);

        if ($setProject) {
            $project = $this->routeApi->getRequestedProject();

            if ($project == null) {
                $userId = $this->routeApi->getUserId();
                $project = "default";
                if (!empty($userId)) {
                    $project = $this->fetchUserProject->fetchOrDefault($userId, $host->getHostId());
                }
            }
            $client->setProject($project);
        }

        return $client;
    }

    private function createFullcertPath(string $certName)
    {
        return $_ENV["LXD_CERTS_DIR"] . $certName;
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

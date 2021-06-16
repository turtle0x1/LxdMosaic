<?php
namespace dhope0000\LXDClient\Model\Client;

use GuzzleHttp\Client as GuzzleClient;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use \Opensaucesystems\Lxd\Client;
use dhope0000\LXDClient\App\RouteApi;
use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\User\GetUserProject;

class LxdClient
{
    private $clientBag = [];

    public function __construct(
        RouteApi $routeApi,
        GetUserProject $getUserProject
    ) {
        $this->routeApi = $routeApi;
        $this->getUserProject = $getUserProject;
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
                    $project = $this->getUserProject->getForHost($userId, $host);
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
            ],
            'curl' => [
                CURLOPT_UNIX_SOCKET_PATH => '/var/snap/lxd/common/lxd/unix.socket'
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
        $client = new Client($adapter, null, 'http://unix.socket/');
        $this->clientBag[$urlAndPort] = $client;
        return $client;
    }
}

<?php

namespace dhope0000\LXDClient\Model\Client;

use dhope0000\LXDClient\App\RouteApi;
use dhope0000\LXDClient\Model\Hosts\GetDetails;
use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\User\GetUserProject;
use GuzzleHttp\Client as GuzzleClient;
use Http\Adapter\Guzzle7\Client as GuzzleAdapter;
use Opensaucesystems\Lxd\Client;

class LxdClient
{
    private $clientBag = [];

    public function __construct(
        private readonly RouteApi $routeApi,
        private readonly GetUserProject $getUserProject,
        private readonly GetDetails $getDetails
    ) {
    }

    public function getClientWithHost(Host $host, $checkCache = true, $setProject = true)
    {
        if ($checkCache && isset($this->clientBag[$host->getUrl()])) {
            return $this->clientBag[$host->getUrl()];
        }

        $socketPath = $this->getDetails->getSocketPath($host->getHostId());
        $certPath = $this->createFullcertPath($host->getCertPath());
        $config = $this->createConfigArray($certPath, $socketPath);
        $client = $this->createNewClient($host->getUrl(), $config);

        if ($setProject) {
            $project = $this->routeApi->getRequestedProject();

            if ($project == null) {
                $userId = $this->routeApi->getUserId();
                $project = 'default';
                if (!empty($userId)) {
                    $project = $this->getUserProject->getForHost($userId, $host);
                }
            }
            $client->setProject($project);
        }

        return $client;
    }

    public function createConfigArray($certLocation, $socketPath)
    {
        $certPath = realpath($certLocation);

        if ($certPath === false) {
            throw new \Exception('Certificate has gone walk abouts', 1);
        }

        $config = [
            'verify' => false,
            'cert' => [$certPath, ''],
        ];

        if ($socketPath !== null) {
            $config['curl'] = [
                CURLOPT_UNIX_SOCKET_PATH => $socketPath,
            ];
        }

        return $config;
    }

    public function createNewClient($urlAndPort, $config)
    {
        $s = $urlAndPort;
        if (isset($config['curl']) && isset($config['curl'][CURLOPT_UNIX_SOCKET_PATH])) {
            $s = 'http://unix.socket/';
        }
        $guzzle = new GuzzleClient($config);
        $adapter = new GuzzleAdapter($guzzle);
        $client = new Client($adapter, null, $s);
        $this->clientBag[$urlAndPort] = $client;
        return $client;
    }

    private function createFullcertPath(string $certName)
    {
        return $_ENV['LXD_CERTS_DIR'] . $certName;
    }
}

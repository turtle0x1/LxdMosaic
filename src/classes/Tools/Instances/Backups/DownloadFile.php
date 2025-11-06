<?php

namespace dhope0000\LXDClient\Tools\Instances\Backups;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Model\Hosts\GetDetails;
use dhope0000\LXDClient\Objects\Host;
use GuzzleHttp\Client;

class DownloadFile
{
    public function __construct(
        private readonly LxdClient $lxdClient,
        private readonly GetDetails $getDetails
    ) {
    }

    public function download(Host $host, string $project, $instance, string $backupFilePath, $backup)
    {
        $certPath = $_ENV['LXD_CERTS_DIR'] . $host->getCertPath();

        $socketPath = $this->getDetails->getSocketPath($host->getHostId());

        $config = $this->lxdClient->createConfigArray($certPath, $socketPath);

        $config = array_merge($config, [
            'sink' => $backupFilePath,
        ]);

        $client = new Client($config);

        try {
            $client->get("{$host->getUrl()}/1.0/instances/{$instance}/backups/{$backup}/export?project={$project}");
        } catch (\Throwable $e) {
            // Remove any file because it will be filled with the LXD response /
            // junk
            if (is_file($backupFilePath)) {
                unlink($backupFilePath);
            }

            throw $e;
        }
        return true;
    }
}

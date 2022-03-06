<?php

namespace dhope0000\LXDClient\Tools\Instances\Backups;

use dhope0000\LXDClient\Model\Client\LxdClient;
use GuzzleHttp\Client;
use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Model\Hosts\GetDetails;

class DownloadFile
{
    public function __construct(LxdClient $lxdClient, GetDetails $getDetails)
    {
        $this->lxdClient = $lxdClient;
        $this->getDetails = $getDetails;
    }

    public function download(Host $host, string $project, $instance, string $backupFilePath, $backup)
    {
        $certPath = $_ENV["LXD_CERTS_DIR"] .  $host->getCertPath();

        $socketPath = $this->getDetails->getSocketPath($host->getHostId());

        $config = $this->lxdClient->createConfigArray($certPath, $socketPath);

        $config = array_merge($config, [
          'sink' => $backupFilePath,
        ]);

        $client = new Client($config);

        try {
            $client->get("{$host->getUrl()}/1.0/instances/$instance/backups/$backup/export?project={$project}");
        } catch (\Throwable $e) {
            // Remove any file because it will be filled with the LXD response /
            // junk
            unlink($backupFilePath);
            throw $e;
        }
        return true;
    }
}

<?php

namespace dhope0000\LXDClient\Tools\Instances\Files;

use dhope0000\LXDClient\Model\Client\LxdClient;

class UploadFiles
{
    private $lxdClient;

    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function upload(int $hostId, string $instance, string $path, array $files)
    {
        $client = $this->lxdClient->getANewClient($hostId);

        foreach ($files as $file) {
            $content = file_get_contents($file['tmp_name']);
            $localPath = $path . "/" . $file["name"];
            $response = $client->instances->files->write($instance, $localPath, $content);
        }

        return true;
    }
}

<?php

namespace dhope0000\LXDClient\Tools\Containers\Files;

use dhope0000\LXDClient\Model\Client\LxdClient;

class UploadFiles
{
    private $lxdClient;

    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function upload(int $hostId, string $container, string $path, array $files)
    {
        $client = $this->lxdClient->getANewClient($hostId);

        foreach ($files as $file) {
            $content = file_get_contents($file['tmp_name']);
            $localPath = $path . "/" . $file["name"];
            $response = $client->instances->files->write($container, $localPath, $content);
        }

        return true;
    }
}

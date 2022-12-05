<?php

namespace dhope0000\LXDClient\Tools\Instances\Files;

use dhope0000\LXDClient\Objects\Host;

class UploadFiles
{
    public function upload(Host $host, string $instance, string $path, array $files) :bool
    {
        foreach ($files as $file) {
            $content = file_get_contents($file['tmp_name']);

            if ($content == false) {
                throw new \Exception("Couldn't process uploaded file", 1);
            }

            $localPath = $path . "/" . $file["name"];
            $response = $host->instances->files->write($instance, $localPath, $content);
        }

        return true;
    }
}

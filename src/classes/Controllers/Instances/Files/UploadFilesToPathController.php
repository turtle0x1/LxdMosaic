<?php

namespace dhope0000\LXDClient\Controllers\Instances\Files;

use dhope0000\LXDClient\Objects\Host;
use \dhope0000\LXDClient\Tools\Instances\Files\UploadFiles;

class UploadFilesToPathController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $uploadFiles;

    public function __construct(UploadFiles $uploadFiles)
    {
        $this->uploadFiles = $uploadFiles;
    }

    public function upload(
        Host $host,
        string $container,
        string $path
    ) {
        $response = $this->uploadFiles->upload($host, $container, $path, $_FILES);

        return ["state"=>"success", "message"=>"Uploaded file", "lxdResponse"=>$response];
    }
}

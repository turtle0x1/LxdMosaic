<?php

namespace dhope0000\LXDClient\Controllers\Containers\Files;

use \dhope0000\LXDClient\Tools\Containers\Files\UploadFiles;

class UploadFilesToPathController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $uploadFiles;

    public function __construct(UploadFiles $uploadFiles)
    {
        $this->uploadFiles = $uploadFiles;
    }

    public function upload(
        int $hostId,
        string $container,
        string $path
    ) {
        $response = $this->uploadFiles->upload($hostId, $container, $path, $_FILES);

        return ["state"=>"success", "message"=>"Uploaded file", "lxdResponse"=>$response];
    }
}

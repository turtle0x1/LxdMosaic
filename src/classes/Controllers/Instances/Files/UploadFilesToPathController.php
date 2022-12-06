<?php

namespace dhope0000\LXDClient\Controllers\Instances\Files;

use dhope0000\LXDClient\Objects\Host;
use \dhope0000\LXDClient\Tools\Instances\Files\UploadFiles;
use Symfony\Component\Routing\Annotation\Route;

class UploadFilesToPathController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private UploadFiles $uploadFiles;

    public function __construct(UploadFiles $uploadFiles)
    {
        $this->uploadFiles = $uploadFiles;
    }
    /**
     * @Route("", name="Upload File To Instance")
     */
    public function upload(
        Host $host,
        string $container,
        string $path
    ) {
        $response = $this->uploadFiles->upload($host, $container, $path, $_FILES);

        return ["state"=>"success", "message"=>"Uploaded file", "lxdResponse"=>$response];
    }
}

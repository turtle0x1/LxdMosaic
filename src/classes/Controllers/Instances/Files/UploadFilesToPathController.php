<?php

namespace dhope0000\LXDClient\Controllers\Instances\Files;

use dhope0000\LXDClient\Objects\Host;
use \dhope0000\LXDClient\Tools\Instances\Files\UploadFiles;
use Symfony\Component\Routing\Annotation\Route;

class UploadFilesToPathController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $uploadFiles;

    public function __construct(UploadFiles $uploadFiles)
    {
        $this->uploadFiles = $uploadFiles;
    }
    /**
     * @Route("/api/Instances/Files/UploadFilesToPathController/upload", methods={"POST"}, name="Upload File To Instance")
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

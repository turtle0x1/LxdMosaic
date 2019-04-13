<?php

namespace dhope0000\LxdClient\Controllers\Images;

use dhope0000\LXDClient\Tools\Images\DeleteImages;

class DeleteImagesController
{
    public function __construct(DeleteImages $deleteImages)
    {
        $this->deleteImages = $deleteImages;
    }

    public function delete($imageData)
    {
        $lxdResponse = $this->deleteImages->delete($imageData);
        return ["state"=>"success", "message"=>"Deleting Image", "lxdResponse"=>$lxdResponse];
    }
}

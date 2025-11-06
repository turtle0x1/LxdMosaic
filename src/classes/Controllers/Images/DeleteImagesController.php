<?php

namespace dhope0000\LXDClient\Controllers\Images;

use dhope0000\LXDClient\Tools\Images\DeleteImages;
use Symfony\Component\Routing\Annotation\Route;

class DeleteImagesController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly DeleteImages $deleteImages
    ) {
    }

    /**
     * @Route("/api/Images/DeleteImagesController/delete", name="Delete Images From Hosts", methods={"POST"})
     */
    public function delete(int $userId, $imageData)
    {
        $lxdResponse = $this->deleteImages->delete($userId, $imageData);
        return [
            'state' => 'success',
            'message' => 'Deleting Image',
            'lxdResponse' => $lxdResponse,
        ];
    }
}

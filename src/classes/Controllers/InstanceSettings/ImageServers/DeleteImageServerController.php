<?php

namespace dhope0000\LXDClient\Controllers\InstanceSettings\ImageServers;

use dhope0000\LXDClient\Model\InstanceSettings\ImageServers\DeleteImageServer;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use Symfony\Component\Routing\Annotation\Route;

class DeleteImageServerController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly FetchUserDetails $fetchUserDetails,
        private readonly DeleteImageServer $deleteImageServer
    ) {
    }

    /**
     * @Route("/api/InstanceSettings/ImageServers/DeleteImageServerController/delete", name="Delete an image server", methods={"POST"})
     */
    public function delete(string $userId, string $alias)
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId);
        if (!$isAdmin) {
            throw new \Exception('No access', 1);
        }
        $this->deleteImageServer->delete($alias);
        return [
            'state' => 'success',
            'message' => 'Deleted image server',
        ];
    }
}

<?php

namespace dhope0000\LXDClient\Controllers\InstanceSettings\ImageServers;

use dhope0000\LXDClient\Model\InstanceSettings\ImageServers\FetchImageServers;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use Symfony\Component\Routing\Attribute\Route;

class GetAllImageServersListController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly FetchUserDetails $fetchUserDetails,
        private readonly FetchImageServers $fetchImageServers
    ) {
    }

    #[Route(path: '/api/InstanceSettings/ImageServers/GetAllImageServersListController/all', name: 'Get all image servers list', methods: ['POST'])]
    public function all(string $userId)
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId);
        if (!$isAdmin) {
            throw new \Exception('No access', 1);
        }
        $aliases = $this->fetchImageServers->fetchAll();
        return [
            'state' => 'success',
            'servers' => $aliases,
        ];
    }
}

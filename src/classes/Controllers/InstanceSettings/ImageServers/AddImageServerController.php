<?php

namespace dhope0000\LXDClient\Controllers\InstanceSettings\ImageServers;

use dhope0000\LXDClient\Constants\ImageServerType;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Tools\InstanceSettings\ImageServers\AddImageServer;
use Symfony\Component\Routing\Attribute\Route;

class AddImageServerController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly FetchUserDetails $fetchUserDetails,
        private readonly AddImageServer $addImageServer
    ) {
    }

    #[Route(path: '/api/InstanceSettings/ImageServers/AddImageServerController/add', name: 'Add a new image server', methods: ['POST'])]
    public function add(string $userId, string $alias, string $url)
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId);
        if (!$isAdmin) {
            throw new \Exception('No access', 1);
        }
        $this->addImageServer->add($alias, $url, ImageServerType::SIMPLE_STREAMS);
        return [
            'state' => 'success',
            'message' => 'Added image server',
        ];
    }
}

<?php

namespace dhope0000\LXDClient\Controllers\CloudConfig;

use dhope0000\LXDClient\Model\CloudConfig\DeleteCloudConfig;
use Symfony\Component\Routing\Attribute\Route;

class DeleteController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly DeleteCloudConfig $deleteCloudConfig
    ) {
    }

    #[Route(path: '/api/CloudConfig/DeleteController/delete', name: 'Delete Cloud Config', methods: ['POST'])]
    public function delete(int $cloudConfigId)
    {
        $this->deleteCloudConfig->delete($cloudConfigId);
        return [
            'state' => 'success',
            'message' => 'Deleted Cloud Config',
        ];
    }
}

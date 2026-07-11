<?php

namespace dhope0000\LXDClient\Controllers\InstanceSettings\RecordedActions;

use dhope0000\LXDClient\Tools\InstanceSettings\RecordActions\GetActions;
use Symfony\Component\Routing\Attribute\Route;

class GetLastController
{
    public function __construct(
        private readonly GetActions $getActions
    ) {
    }

    #[Route(path: '/api/InstanceSettings/RecordedActions/GetLastController/get', name: 'Get recent actions', methods: ['POST'])]
    public function get(int $userId, int $ammount)
    {
        return $this->getActions->get($userId, $ammount);
    }
}

<?php

namespace dhope0000\LXDClient\Controllers\Instances;

use dhope0000\LXDClient\Constants\StateConstants as StateCon;
use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\State;
use Symfony\Component\Routing\Attribute\Route;

class StateController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly State $state
    ) {
    }

    #[Route(path: '/api/Instances/StateController/start', name: 'Start Instance', methods: ['POST'])]
    public function start(Host $host, $container)
    {
        $this->state->change($host, $container, StateCon::START);
        return [
            'state' => 'success',
            'message' => "Starting {$host->getAlias()}/{$container}",
            'code' => 101,
        ];
    }

    #[Route(path: '/api/Instances/StateController/stop', name: 'Stop Instance', methods: ['POST'])]
    public function stop(Host $host, $container)
    {
        $this->state->change($host, $container, StateCon::STOP);
        return [
            'state' => 'success',
            'message' => "Stopping {$host->getAlias()}alias/{$container}",
            'code' => 102,
        ];
    }

    #[Route(path: '/api/Instances/StateController/restart', name: 'Restart Instance', methods: ['POST'])]
    public function restart(Host $host, $container)
    {
        $this->state->change($host, $container, StateCon::RESTART);
        return [
            'state' => 'success',
            'message' => "Restarting {$host->getAlias()}/{$container}",
            'code' => 101,
        ];
    }

    #[Route(path: '/api/Instances/StateController/freeze', name: 'Freeze Instance', methods: ['POST'])]
    public function freeze(Host $host, $container)
    {
        $this->state->change($host, $container, StateCon::FREEZE);
        return [
            'state' => 'success',
            'message' => "Freezing {$host->getAlias()}/{$container}",
            'code' => 110,
        ];
    }

    #[Route(path: '/api/Instances/StateController/unfreeze', name: 'UnFreeze Instance', methods: ['POST'])]
    public function unfreeze(Host $host, $container)
    {
        $this->state->change($host, $container, StateCon::UNFREEZE);
        return [
            'state' => 'success',
            'message' => "Unfreezing {$host->getAlias()}/{$container}",
            'code' => 101,
        ];
    }
}

<?php
namespace dhope0000\LXDClient\Controllers\Containers;

use dhope0000\LXDClient\Tools\Containers\State;
use dhope0000\LXDClient\Constants\StateConstants as StateCon;

class StateController
{
    public function __construct(State $state)
    {
        $this->state = $state;
    }

    public function startContainer($hostId, $container)
    {
        $this->state->change($hostId, $container, StateCon::START);
        return ["state"=>"success", "message"=>"Starting $hostId/$container", "code"=>101];
    }

    public function stopContainer($hostId, $container)
    {
        $this->state->change($hostId, $container, StateCon::STOP);
        return ["state"=>"success", "message"=>"Stoping $hostId/$container", "code"=>102];
    }

    public function restartContainer($hostId, $container)
    {
        $this->state->change($hostId, $container, StateCon::RESTART);
        return ["state"=>"success", "message"=>"Restarting $hostId/$container", "code"=>101];
    }
    public function freezeContainer($hostId, $container)
    {
        $this->state->change($hostId, $container, StateCon::FREEZE);
        return ["state"=>"success", "message"=>"Freezing $hostId/$container", "code"=>110];
    }
    public function unfreezeContainer($hostId, $container)
    {
        $this->state->change($hostId, $container, StateCon::UNFREEZE);
        return ["state"=>"success", "message"=>"Unfreezing $hostId/$container", "code"=>101];
    }
}

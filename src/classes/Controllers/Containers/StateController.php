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

    public function startContainer($host, $container)
    {
        $this->state->change($host, $container, StateCon::START);
        return ["state"=>"success", "message"=>"Starting $host/$container", "code"=>101];
    }

    public function stopContainer($host, $container)
    {
        $this->state->change($host, $container, StateCon::STOP);
        return ["state"=>"success", "message"=>"Stoping $host/$container", "code"=>102];
    }

    public function restartContainer($host, $container)
    {
        $this->state->change($host, $container, StateCon::RESTART);
        return ["state"=>"success", "message"=>"Restarting $host/$container", "code"=>101];
    }
    public function freezeContainer($host, $container)
    {
        $this->state->change($host, $container, StateCon::FREEZE);
        return ["state"=>"success", "message"=>"Freezing $host/$container", "code"=>110];
    }
    public function unfreezeContainer($host, $container)
    {
        $this->state->change($host, $container, StateCon::UNFREEZE);
        return ["state"=>"success", "message"=>"Unfreezing $host/$container", "code"=>101];
    }
}

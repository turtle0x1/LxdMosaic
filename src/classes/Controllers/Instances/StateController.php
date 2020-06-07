<?php
namespace dhope0000\LXDClient\Controllers\Instances;

use dhope0000\LXDClient\Tools\Instances\State;
use dhope0000\LXDClient\Constants\StateConstants as StateCon;
use dhope0000\LXDClient\Objects\Host;

class StateController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(State $state)
    {
        $this->state = $state;
    }

    public function start(Host $host, $container, string $alias = null)
    {
        $this->state->change($host, $container, StateCon::START);
        return ["state"=>"success", "message"=>"Starting $alias/$container", "code"=>101];
    }

    public function stop(Host $host, $container, string $alias = null)
    {
        $this->state->change($host, $container, StateCon::STOP);
        return ["state"=>"success", "message"=>"Stopping $alias/$container", "code"=>102];
    }

    public function restart(Host $host, $container, string $alias = null)
    {
        $this->state->change($host, $container, StateCon::RESTART);
        return ["state"=>"success", "message"=>"Restarting $alias/$container", "code"=>101];
    }
    public function freeze(Host $host, $container, string $alias = null)
    {
        $this->state->change($host, $container, StateCon::FREEZE);
        return ["state"=>"success", "message"=>"Freezing $alias/$container", "code"=>110];
    }
    public function unfreeze(Host $host, $container, string $alias = null)
    {
        $this->state->change($host, $container, StateCon::UNFREEZE);
        return ["state"=>"success", "message"=>"Unfreezing $alias/$container", "code"=>101];
    }
}

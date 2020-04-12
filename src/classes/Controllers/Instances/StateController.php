<?php
namespace dhope0000\LXDClient\Controllers\Instances;

use dhope0000\LXDClient\Tools\Instances\State;
use dhope0000\LXDClient\Constants\StateConstants as StateCon;

class StateController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(State $state)
    {
        $this->state = $state;
    }

    public function start($hostId, $container, string $alias = null)
    {
        $this->state->change($hostId, $container, StateCon::START);
        return ["state"=>"success", "message"=>"Starting $alias/$container", "code"=>101];
    }

    public function stop($hostId, $container, string $alias = null)
    {
        $this->state->change($hostId, $container, StateCon::STOP);
        return ["state"=>"success", "message"=>"Stopping $alias/$container", "code"=>102];
    }

    public function restart($hostId, $container, string $alias = null)
    {
        $this->state->change($hostId, $container, StateCon::RESTART);
        return ["state"=>"success", "message"=>"Restarting $alias/$container", "code"=>101];
    }
    public function freeze($hostId, $container, string $alias = null)
    {
        $this->state->change($hostId, $container, StateCon::FREEZE);
        return ["state"=>"success", "message"=>"Freezing $alias/$container", "code"=>110];
    }
    public function unfreeze($hostId, $container, string $alias = null)
    {
        $this->state->change($hostId, $container, StateCon::UNFREEZE);
        return ["state"=>"success", "message"=>"Unfreezing $alias/$container", "code"=>101];
    }
}

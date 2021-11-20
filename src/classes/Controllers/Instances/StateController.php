<?php
namespace dhope0000\LXDClient\Controllers\Instances;

use dhope0000\LXDClient\Tools\Instances\State;
use dhope0000\LXDClient\Constants\StateConstants as StateCon;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class StateController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(State $state)
    {
        $this->state = $state;
    }
    /**
     * @Route("", name="Start Instance")
     */
    public function start(Host $host, $container)
    {
        $this->state->change($host, $container, StateCon::START);
        return ["state"=>"success", "message"=>"Starting {$host->getAlias()}/$container", "code"=>101];
    }
    /**
     * @Route("", name="Stop Instance")
     */
    public function stop(Host $host, $container)
    {
        $this->state->change($host, $container, StateCon::STOP);
        return ["state"=>"success", "message"=>"Stoppin{$host->getAlias()}alias/$container", "code"=>102];
    }
    /**
     * @Route("", name="Restart Instance")
     */
    public function restart(Host $host, $container)
    {
        $this->state->change($host, $container, StateCon::RESTART);
        return ["state"=>"success", "message"=>"Restarting {$host->getAlias()}/$container", "code"=>101];
    }
    /**
     * @Route("", name="Freeze Instance")
     */
    public function freeze(Host $host, $container)
    {
        $this->state->change($host, $container, StateCon::FREEZE);
        return ["state"=>"success", "message"=>"Freezing {$host->getAlias()}/$container", "code"=>110];
    }
    /**
     * @Route("", name="UnFreeze Instance")
     */
    public function unfreeze(Host $host, $container)
    {
        $this->state->change($host, $container, StateCon::UNFREEZE);
        return ["state"=>"success", "message"=>"Unfreezing {$host->getAlias()}/$container", "code"=>101];
    }
}

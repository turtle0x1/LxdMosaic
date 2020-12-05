<?php

namespace dhope0000\LXDClient\Controllers\InstanceSettings;

use dhope0000\LXDClient\Tools\InstanceSettings\FirstRun;
use Symfony\Component\Routing\Annotation\Route;

class FirstRunController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $firstRun;

    public function __construct(FirstRun $firstRun)
    {
        $this->firstRun = $firstRun;
    }
    /**
     * @Route("", name="Run LXDMosaic First Run")
     */
    public function run(array $hosts, string $adminPassword, array $users = [])
    {
        $this->firstRun->run($hosts, $adminPassword, $users);
        return ["state"=>"success", "message"=>"Setup LXD Mosaic"];
    }
}

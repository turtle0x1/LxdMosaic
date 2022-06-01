<?php

namespace dhope0000\LXDClient\Controllers\InstanceSettings;

use dhope0000\LXDClient\Tools\InstanceSettings\FirstRun;
use Symfony\Component\Routing\Annotation\Route;
use \DI\Container;

class FirstRunController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $firstRun;
    private $container;

    public function __construct(FirstRun $firstRun, Container $container)
    {
        $this->firstRun = $firstRun;
        $this->container = $container;
    }
    /**
     * @Route("", name="Run LXDMosaic First Run")
     */
    public function run(array $hosts, string $adminPassword, array $settings = [])
    {
        $this->container->call(["dhope0000\LXDClient\Model\Database\Database", "beginTransaction"]);
        $this->firstRun->run($hosts, $adminPassword, $settings);
        $this->container->call(["dhope0000\LXDClient\Model\Database\Database", "commitTransaction"]);
        return ["state"=>"success", "message"=>"Setup LXD Mosaic"];
    }
}

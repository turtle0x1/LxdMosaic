<?php
namespace dhope0000\LXDClient\Controllers\Instances;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\GetInstanceSettings;

class GetCurrentInstanceSettingsController
{
    private $getInstanceSettings;
    
    public function __construct(GetInstanceSettings $getInstanceSettings)
    {
        $this->getInstanceSettings = $getInstanceSettings;
    }

    public function get(Host $host, string $container)
    {
        return $this->getInstanceSettings->get($host, $container);
    }
}

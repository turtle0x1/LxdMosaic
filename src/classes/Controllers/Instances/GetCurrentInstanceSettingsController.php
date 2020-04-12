<?php
namespace dhope0000\LXDClient\Controllers\Instances;

use dhope0000\LXDClient\Tools\Instances\GetInstanceSettings;

class GetCurrentInstanceSettingsController
{
    public function __construct(GetInstanceSettings $getInstanceSettings)
    {
        $this->getInstanceSettings = $getInstanceSettings;
    }

    public function get(int $hostId, string $container)
    {
        return $this->getInstanceSettings->get($hostId, $container);
    }
}

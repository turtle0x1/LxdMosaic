<?php
namespace dhope0000\LXDClient\Controllers\Instances;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\GetInstanceSettings;
use Symfony\Component\Routing\Annotation\Route;

class GetCurrentInstanceSettingsController
{
    public function __construct(GetInstanceSettings $getInstanceSettings)
    {
        $this->getInstanceSettings = $getInstanceSettings;
    }
    /**
     * @Route("/api/Instances/GetCurrentInstanceSettingsController/get", methods={"POST"}, name="Get all instance settings")
     */
    public function get(Host $host, string $container)
    {
        return $this->getInstanceSettings->get($host, $container);
    }
}

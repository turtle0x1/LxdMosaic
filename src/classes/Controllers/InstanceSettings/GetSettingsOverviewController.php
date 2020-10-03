<?php

namespace dhope0000\LXDClient\Controllers\InstanceSettings;

use dhope0000\LXDClient\Tools\InstanceSettings\GetSettingsOverview;
use Symfony\Component\Routing\Annotation\Route;

class GetSettingsOverviewController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $getSettingsOverview;

    public function __construct(GetSettingsOverview $getSettingsOverview)
    {
        $this->getSettingsOverview = $getSettingsOverview;
    }
    /**
     * @Route("", name="Get LXDMosaic Settings Overview")
     */
    public function get(string $userId)
    {
        return $this->getSettingsOverview->get($userId);
    }
}

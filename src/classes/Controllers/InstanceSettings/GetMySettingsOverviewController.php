<?php

namespace dhope0000\LXDClient\Controllers\InstanceSettings;

use dhope0000\LXDClient\Tools\InstanceSettings\GetSettingsOverview;
use Symfony\Component\Routing\Annotation\Route;

class GetMySettingsOverviewController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private GetSettingsOverview $getSettingsOverview;

    public function __construct(GetSettingsOverview $getSettingsOverview)
    {
        $this->getSettingsOverview = $getSettingsOverview;
    }
    /**
     * @Route("", name="Get My LXDMosaic Settings Overview")
     */
    public function get(string $userId)
    {
        return $this->getSettingsOverview->get($userId);
    }
}

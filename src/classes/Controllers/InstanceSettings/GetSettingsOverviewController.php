<?php

namespace dhope0000\LXDClient\Controllers\InstanceSettings;

use dhope0000\LXDClient\Tools\InstanceSettings\GetSettingsOverview;

class GetSettingsOverviewController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $saveSettings;

    public function __construct(GetSettingsOverview $getSettingsOverview)
    {
        $this->getSettingsOverview = $getSettingsOverview;
    }

    public function get()
    {
        return $this->getSettingsOverview->get();
    }
}

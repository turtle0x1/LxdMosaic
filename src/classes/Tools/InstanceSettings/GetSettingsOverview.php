<?php

namespace dhope0000\LXDClient\Tools\InstanceSettings;

use dhope0000\LXDClient\Tools\Utilities\IsUpToDate;

class GetSettingsOverview
{
    public function __construct()
    {
    }

    public function get() :array
    {
        return [
            "versionDetails"=>IsUpToDate::isIt()
        ];
    }
}

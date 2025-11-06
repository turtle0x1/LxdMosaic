<?php

namespace dhope0000\LXDClient\Controllers\InstanceSettings;

use dhope0000\LXDClient\Tools\InstanceSettings\GetSettingsOverview;
use Symfony\Component\Routing\Annotation\Route;

class GetMySettingsOverviewController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly GetSettingsOverview $getSettingsOverview
    ) {
    }

    /**
     * @Route("/api/InstanceSettings/GetMySettingsOverviewController/get", name="Get My LXDMosaic Settings Overview", methods={"POST"})
     */
    public function get(string $userId)
    {
        return $this->getSettingsOverview->get($userId);
    }
}

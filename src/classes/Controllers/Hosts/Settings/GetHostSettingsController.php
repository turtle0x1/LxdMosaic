<?php

namespace dhope0000\LXDClient\Controllers\Hosts\Settings;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use Symfony\Component\Routing\Annotation\Route;

class GetHostSettingsController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly ValidatePermissions $validatePermissions
    ) {
    }

    /**
     * @Route("/api/Hosts/Settings/GetHostSettingsController/get", name="Get hosts settings", methods={"POST"})
     */
    public function get($userId, Host $host)
    {
        $this->validatePermissions->isAdminOrThrow($userId);
        return $host->host->info()['config'];
    }
}

<?php
namespace dhope0000\LXDClient\Controllers\Hosts;

use dhope0000\LXDClient\Tools\Hosts\GetHostsOverview;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;

class GetHostsController
{
    private GetHostsOverview $getHostsOverview;
    private ValidatePermissions $validatePermissions;

    public function __construct(GetHostsOverview $getHostsOverview, ValidatePermissions $validatePermissions)
    {
        $this->getHostsOverview = $getHostsOverview;
        $this->validatePermissions = $validatePermissions;
    }

    public function getAllHosts(int $userId)
    {
        $this->validatePermissions->isAdminOrThrow($userId);
        return $this->getHostsOverview->get();
    }
}

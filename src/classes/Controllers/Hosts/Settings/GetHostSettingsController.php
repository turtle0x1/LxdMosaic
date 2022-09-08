<?php
namespace dhope0000\LXDClient\Controllers\Hosts\Settings;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use Symfony\Component\Routing\Annotation\Route;

class GetHostSettingsController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $validatePermissions;

    public function __construct(ValidatePermissions $validatePermissions)
    {
        $this->validatePermissions = $validatePermissions;
    }
    /**
     * @Route("", name="Get hosts settings")
     */
    public function get($userId, Host $host)
    {
        $this->validatePermissions->isAdminOrThrow($userId);
        return $host->host->info()["config"];
    }
}

<?php
namespace dhope0000\LXDClient\Controllers\Hosts\Settings;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use Symfony\Component\Routing\Annotation\Route;

class UpdateHostSettingsController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private ValidatePermissions $validatePermissions;

    public function __construct(ValidatePermissions $validatePermissions)
    {
        $this->validatePermissions = $validatePermissions;
    }
    /**
     * @Route("", name="Get hosts settings")
     */
    public function update(int $userId, Host $host, array $settings)
    {
        $this->validatePermissions->isAdminOrThrow($userId);
        $info = $host->host->info();
        foreach ($settings as $key=>$value) {
            $info["config"][$key] = $value;
        }
        $host->host->replace($info['config']);
        return ["state"=>"success", "messages"=>"Updated LXD Settings"];
    }
}

<?php
namespace dhope0000\LXDClient\Controllers\Hosts\Settings;

use dhope0000\LXDClient\Tools\Hosts\Settings\UpdateHostSettings;
use Symfony\Component\Routing\Annotation\Route;

class UpdateSettingsController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private UpdateHostSettings $updateHostSettings;

    public function __construct(UpdateHostSettings $updateHostSettings)
    {
        $this->updateHostSettings = $updateHostSettings;
    }
    /**
     * @Route("", name="Update hosts settings")
     */
    public function update(int $userId, int $hostId, string $alias, int $supportsLoadAverages)
    {
        $this->updateHostSettings->update($userId, $hostId, $alias, $supportsLoadAverages);
        return ["state"=>"success", "messages"=>"Updated Settings"];
    }
}

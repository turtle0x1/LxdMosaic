<?php
namespace dhope0000\LXDClient\Controllers\Containers;

use dhope0000\LXDClient\Tools\Containers\SetContainerSettings;

class SetSettingsController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(SetContainerSettings $setContainerSettings)
    {
        $this->setContainerSettings = $setContainerSettings;
    }

    public function set(int $hostId, string $container, array $settings)
    {
        $this->setContainerSettings->set($hostId, $container, $settings);
        return ["state"=>"success", "message"=>"Updated container settings"];
    }
}

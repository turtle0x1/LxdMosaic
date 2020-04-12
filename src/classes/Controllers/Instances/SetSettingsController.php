<?php
namespace dhope0000\LXDClient\Controllers\Instances;

use dhope0000\LXDClient\Tools\Instances\SetInstanceSettings;

class SetSettingsController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(SetInstanceSettings $setInstanceSettings)
    {
        $this->setInstanceSettings = $setInstanceSettings;
    }

    public function set(int $hostId, string $container, array $settings)
    {
        $this->setInstanceSettings->set($hostId, $container, $settings);
        return ["state"=>"success", "message"=>"Updated container settings"];
    }
}

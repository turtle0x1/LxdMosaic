<?php
namespace dhope0000\LXDClient\Controllers\Containers;

use dhope0000\LXDClient\Tools\Containers\SetContainerSettings;

class SetSettingsController
{
    public function __construct(SetContainerSettings $setContainerSettings)
    {
        $this->setContainerSettings = $setContainerSettings;
    }

    public function set(string $host, string $container, array $settings)
    {
        $this->setContainerSettings->set($host, $container, $settings);
        return ["state"=>"success", "message"=>"Updated container settings"];
    }
}

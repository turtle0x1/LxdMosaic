<?php
namespace dhope0000\LXDClient\Controllers\Instances;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\SetInstanceSettings;
use Symfony\Component\Routing\Annotation\Route;

class SetSettingsController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $setInstanceSettings;

    public function __construct(SetInstanceSettings $setInstanceSettings)
    {
        $this->setInstanceSettings = $setInstanceSettings;
    }
    /**
     * @Route("", name="Set Instance Settings")
     */
    public function set(Host $host, string $container, array $settings)
    {
        $this->setInstanceSettings->set($host, $container, $settings);
        return ["state"=>"success", "message"=>"Updated container settings"];
    }
}

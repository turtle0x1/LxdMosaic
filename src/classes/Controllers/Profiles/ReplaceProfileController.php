<?php
namespace dhope0000\LXDClient\Controllers\Profiles;

use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class ReplaceProfileController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    /**
     * @Route("", name="Replace Profile")
     */
    public function replace(
        Host $host,
        string $name,
        string $description,
        array $config = [],
        array $devices = []
    ) {
        $host->profiles->replace($name, $description, $config, $devices);
        return ["state"=>"success", "message"=>"Updated profile"];
    }
}

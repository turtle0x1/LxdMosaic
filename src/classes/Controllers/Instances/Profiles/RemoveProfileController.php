<?php
namespace dhope0000\LXDClient\Controllers\Instances\Profiles;

use dhope0000\LXDClient\Tools\Profiles\RemoveProfiles;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class RemoveProfileController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $removeProfiles;
    
    public function __construct(RemoveProfiles $removeProfiles)
    {
        $this->removeProfiles = $removeProfiles;
    }
    /**
     * @Route("", name="Remove profile from instance")
     */
    public function remove(Host $host, string $container, string $profile)
    {
        $result = $this->removeProfiles->remove($host, $container, [$profile], true);

        if ($result["err"]) {
            throw new \Exception($result["err"], 1);
        }

        return ["state"=>"success", "message"=>"Removed profile"];
    }
}

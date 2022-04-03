<?php
namespace dhope0000\LXDClient\Controllers\Instances\Profiles;

use dhope0000\LXDClient\Tools\Profiles\AssignProfiles;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class AssignProfilesController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(AssignProfiles $assignProfiles)
    {
        $this->assignProfiles = $assignProfiles;
    }
    /**
     * @Route("/api/Instances/Profiles/AssignProfilesController/assign", methods={"POST"}, name="Assign profiles to instance")
     */
    public function assign(Host $host, string $container, array $profiles)
    {
        $this->assignProfiles->assign($host, $container, $profiles);

        return ["state"=>"success", "message"=>"Assigned profiles"];
    }
}

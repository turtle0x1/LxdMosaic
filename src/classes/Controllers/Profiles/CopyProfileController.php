<?php
namespace dhope0000\LXDClient\Controllers\Profiles;

use dhope0000\LXDClient\Tools\Profiles\Copy;
use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Objects\HostsCollection;
use Symfony\Component\Routing\Annotation\Route;

class CopyProfileController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private Copy $copy;

    public function __construct(Copy $copy)
    {
        $this->copy = $copy;
    }
    /**
     * @Route("",  name="Copy profile")
     */
    public function copyProfile(
        Host $host,
        string $profile,
        HostsCollection $targetHosts,
        string $newName
    ) {
        $response = $this->copy->copyToTargetHosts($host, $profile, $targetHosts, $newName);
        return ["state"=>"success", "message"=>"Copied Profile", "lxdResponse"=>$response];
    }
}

<?php
namespace dhope0000\LXDClient\Controllers\Profiles;

use dhope0000\LXDClient\Tools\Profiles\Copy;

class CopyProfileController
{
    public function __construct(Copy $copy)
    {
        $this->copy = $copy;
    }

    public function copyProfile(
        int $hostId,
        string $profile,
        array $targetHosts,
        string $newName
    ) {
        $response = $this->copy->copyToTargetHosts($hostId, $profile, $targetHosts, $newName);
        return ["state"=>"success", "message"=>"Copied Profile", "lxdResponse"=>$response];
    }
}

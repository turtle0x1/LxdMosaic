<?php
namespace dhope0000\LXDClient\Controllers\Profiles;

use dhope0000\LXDClient\Tools\Profiles\DeleteProfile;
use dhope0000\LXDClient\Objects\Host;

class DeleteProfileController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(DeleteProfile $deleteProfile)
    {
        $this->deleteProfile = $deleteProfile;
    }

    public function delete(Host $host, string $profile)
    {
        $response = $this->deleteProfile->delete($host, $profile);
        return ["state"=>"success", "message"=>"Deleted Profile", "lxdResponse"=>$response];
    }
}

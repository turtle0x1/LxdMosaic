<?php
namespace dhope0000\LXDClient\Controllers\Profiles;

use dhope0000\LXDClient\Tools\Profiles\DeleteProfile;

class DeleteProfileController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(DeleteProfile $deleteProfile)
    {
        $this->deleteProfile = $deleteProfile;
    }

    public function delete(
        string $profile,
        int $hostId
    ) {
        $response = $this->deleteProfile->delete($hostId, $profile);
        return ["state"=>"success", "message"=>"Deleted Profile", "lxdResponse"=>$response];
    }
}

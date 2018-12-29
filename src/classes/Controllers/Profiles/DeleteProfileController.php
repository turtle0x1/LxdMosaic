<?php
namespace dhope0000\LXDClient\Controllers\Profiles;

use dhope0000\LXDClient\Model\Profiles\DeleteProfile;

class DeleteProfileController
{
    public function __construct(DeleteProfile $deleteProfile)
    {
        $this->deleteProfile = $deleteProfile;
    }

    public function delete(
        string $profile,
        string $host
    ) {
        $response = $this->deleteProfile->delete($host, $profile);
        return ["state"=>"success", "message"=>"Deleted Profile", "lxdResponse"=>$response];
    }
}

<?php
namespace dhope0000\LXDClient\Controllers\Profiles;

use dhope0000\LXDClient\Tools\Profiles\Copy;
use dhope0000\LXDClient\Tools\Profiles\CreateProfile;
use dhope0000\LXDClient\Objects\HostsCollection;

class CreateProfileController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(CreateProfile $createProfile)
    {
        $this->createProfile = $createProfile;
    }

    public function create(
        HostsCollection $hosts,
        string $name,
        string $description = '',
        array $config = null,
        array $devices = null
    ) {
        $this->createProfile->createOnHosts(
            $hosts,
            $name,
            $description,
            $config,
            $devices
        );
        return ["state"=>"success", "message"=>"Created Profiles"];
    }
}

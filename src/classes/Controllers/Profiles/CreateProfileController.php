<?php
namespace dhope0000\LXDClient\Controllers\Profiles;

use dhope0000\LXDClient\Tools\Profiles\Copy;
use dhope0000\LXDClient\Tools\Profiles\CreateProfile;
use dhope0000\LXDClient\Objects\HostsCollection;
use Symfony\Component\Routing\Annotation\Route;

class CreateProfileController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $createProfile;

    public function __construct(CreateProfile $createProfile)
    {
        $this->createProfile = $createProfile;
    }

    /**
     * @Route("", name="Create Profile")
     */
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

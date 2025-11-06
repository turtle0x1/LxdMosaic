<?php

namespace dhope0000\LXDClient\Controllers\Profiles;

use dhope0000\LXDClient\Objects\HostsCollection;
use dhope0000\LXDClient\Tools\Profiles\CreateProfile;
use Symfony\Component\Routing\Annotation\Route;

class CreateProfileController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly CreateProfile $createProfile
    ) {
    }

    /**
     * @Route("/api/Profiles/CreateProfileController/create", name="Create Profile", methods={"POST"})
     */
    public function create(
        HostsCollection $hosts,
        string $name,
        string $description = '',
        ?array $config = null,
        ?array $devices = null
    ) {
        $this->createProfile->createOnHosts($hosts, $name, $description, $config, $devices);
        return [
            'state' => 'success',
            'message' => 'Created Profiles',
        ];
    }
}

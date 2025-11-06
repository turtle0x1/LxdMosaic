<?php

namespace dhope0000\LXDClient\Controllers\Profiles;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Profiles\DeleteProfile;
use Symfony\Component\Routing\Annotation\Route;

class DeleteProfileController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly DeleteProfile $deleteProfile
    ) {
    }

    /**
     * @Route("/api/Profiles/DeleteProfileController/delete", name="Delete Profile", methods={"POST"})
     */
    public function delete(Host $host, string $profile)
    {
        $response = $this->deleteProfile->delete($host, $profile);
        return [
            'state' => 'success',
            'message' => 'Deleted Profile',
            'lxdResponse' => $response,
        ];
    }
}

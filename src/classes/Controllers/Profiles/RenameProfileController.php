<?php

namespace dhope0000\LXDClient\Controllers\Profiles;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Profiles\Rename;
use Symfony\Component\Routing\Annotation\Route;

class RenameProfileController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly Rename $rename
    ) {
    }

    /**
     * @Route("/api/Profiles/RenameProfileController/rename", name="Rename Profile", methods={"POST"})
     */
    public function rename(Host $host, string $currentName, string $newProfileName)
    {
        $this->rename->rename($host, $currentName, $newProfileName);
        return [
            'state' => 'success',
            'message' => 'Updated profile name',
        ];
    }
}

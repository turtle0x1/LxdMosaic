<?php
namespace dhope0000\LXDClient\Controllers\Profiles;

use dhope0000\LXDClient\Tools\Profiles\Rename;

class RenameProfileController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(Rename $rename)
    {
        $this->rename = $rename;
    }

    public function rename(
        int $hostId,
        string $currentName,
        string $newProfileName
    ) {
        $this->rename->rename(
            $hostId,
            $currentName,
            $newProfileName
        );
        return ["state"=>"success", "message"=>"Updated profile name"];
    }
}

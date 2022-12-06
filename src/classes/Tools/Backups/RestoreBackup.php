<?php

namespace dhope0000\LXDClient\Tools\Backups;

use dhope0000\LXDClient\Model\Backups\FetchBackup;
use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;

class RestoreBackup
{
    private FetchBackup $fetchBackup;
    private ValidatePermissions $validatePermissions;

    public function __construct(
        FetchBackup $fetchBackup,
        ValidatePermissions $validatePermissions
    ) {
        $this->fetchBackup = $fetchBackup;
        $this->validatePermissions = $validatePermissions;
    }

    public function restore(int $userId, int $backupId, Host $targetHost)
    {
        $backup = $this->fetchBackup->fetch($backupId);

        $this->validatePermissions->canAccessHostProjectOrThrow($userId, $backup["hostId"], $backup["project"]);

        $response = $targetHost->instances->create("", [
            "source"=>"backup",
            "file"=>file_get_contents($backup["localPath"])
        ], true);

        if (isset($response["err"]) && $response["err"] !== "") {
            throw new \Exception($response["err"], 1);
        }

        return $response;
    }
}

<?php

namespace dhope0000\LXDClient\Tools\Backups;

use dhope0000\LXDClient\Model\Backups\FetchBackup;
use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Model\Users\AllowedProjects\FetchAllowedProjects;

class RestoreBackup
{
    private $fetchBackup;

    public function __construct(
        FetchBackup $fetchBackup,
        FetchAllowedProjects $fetchAllowedProjects
    ) {
        $this->fetchBackup = $fetchBackup;
        $this->fetchAllowedProjects = $fetchAllowedProjects;
    }

    public function restore(int $userId, int $backupId, Host $targetHost)
    {
        $backup = $this->fetchBackup->fetch($backupId);

        $allowedProjects = $this->fetchAllowedProjects->fetchForHost($userId, $backup["hostId"]);

        if (!in_array($backup["project"], $allowedProjects)) {
            throw new \Exception("Not allowed to restore this backup", 1);
        }

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

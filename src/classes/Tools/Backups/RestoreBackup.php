<?php

namespace dhope0000\LXDClient\Tools\Backups;

use dhope0000\LXDClient\Model\Backups\FetchBackup;
use dhope0000\LXDClient\Objects\Host;

class RestoreBackup
{
    private $fetchBackup;

    public function __construct(FetchBackup $fetchBackup)
    {
        $this->fetchBackup = $fetchBackup;
    }

    public function restore(int $backupId, Host $targetHost)
    {
        $backup = $this->fetchBackup->fetch($backupId);

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

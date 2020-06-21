<?php

namespace dhope0000\LXDClient\Tools\Instances\Backups;

use dhope0000\LXDClient\Model\Backups\FetchBackup;
use dhope0000\LXDClient\Model\Backups\DeleteBackup;

class DeleteLocalBackup
{
    private $fetchBackup;
    private $deleteBackup;

    public function __construct(FetchBackup $fetchBackup, DeleteBackup $deleteBackup)
    {
        $this->fetchBackup = $fetchBackup;
        $this->deleteBackup = $deleteBackup;
    }

    public function delete(int $backupId)
    {
        $backup = $this->fetchBackup->fetch($backupId);

        if (empty($backup)) {
            throw new \Exception("Cant find backup!", 1);
        }

        if (file_exists($backup["localPath"])) {
            unlink($backup["localPath"]);
        }
        $this->deleteBackup->delete($backupId);
        return true;
    }
}

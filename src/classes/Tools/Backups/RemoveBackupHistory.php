<?php

namespace dhope0000\LXDClient\Tools\Backups;

use dhope0000\LXDClient\Model\InstanceSettings\GetSetting;
use dhope0000\LXDClient\Constants\InstanceSettingsKeys;
use dhope0000\LXDClient\Model\Backups\DeleteBackup;

class RemoveBackupHistory
{
    private GetSetting $getSetting;
    private DeleteBackup $deleteBackup;

    public function __construct(
        GetSetting $getSetting,
        DeleteBackup $deleteBackup
    ) {
        $this->getSetting = $getSetting;
        $this->deleteBackup = $deleteBackup;
    }

    public function remove() :void
    {
        $howFarBack = $this->getSetting->getSettingLatestValue(InstanceSettingsKeys::BACKUP_HISTORY);
        $before = (new \DateTime())->modify($howFarBack);
        $this->deleteBackup->deleteBefore($before);
    }
}

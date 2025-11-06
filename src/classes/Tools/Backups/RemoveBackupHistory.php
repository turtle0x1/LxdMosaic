<?php

namespace dhope0000\LXDClient\Tools\Backups;

use dhope0000\LXDClient\Constants\InstanceSettingsKeys;
use dhope0000\LXDClient\Model\Backups\DeleteBackup;
use dhope0000\LXDClient\Model\InstanceSettings\GetSetting;

class RemoveBackupHistory
{
    public function __construct(
        private readonly GetSetting $getSetting,
        private readonly DeleteBackup $deleteBackup
    ) {
    }

    public function remove()
    {
        $howFarBack = $this->getSetting->getSettingLatestValue(InstanceSettingsKeys::BACKUP_HISTORY);
        $before = (new \DateTime())->modify($howFarBack);
        $this->deleteBackup->deleteBefore($before);
    }
}

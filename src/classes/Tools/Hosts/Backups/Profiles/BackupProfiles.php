<?php

namespace dhope0000\LXDClient\Tools\Hosts\Backups\Profiles;

use dhope0000\LXDClient\Model\Hosts\Backups\Profiles\InsertProfilesBackup;
use dhope0000\LXDClient\Model\InstanceSettings\GetSetting;
use dhope0000\LXDClient\Constants\InstanceSettingsKeys;
use dhope0000\LXDClient\Constants\LxdRecursionLevels;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Yaml\Yaml;

class BackupProfiles
{
    public function __construct(
        GetSetting $getSetting,
        InsertProfilesBackup $insertProfilesBackup
    ) {
        $this->getSetting = $getSetting;
        $this->insertProfilesBackup = $insertProfilesBackup;
    }

    public function create(
        Host $host,
        string $project
    ) {
        $profiles = $host->profiles->all(LxdRecursionLevels::INSTANCE_FULL_RECURSION);
        $zip = new \ZipArchive;

        $backupPath = $this->getSetting->getSettingLatestValue(InstanceSettingsKeys::BACKUP_DIRECTORY);
        $timezone = $this->getSetting->getSettingLatestValue(InstanceSettingsKeys::TIMEZONE);

        $name = (new \DateTime())->setTimezone(new \DateTimeZone($timezone))->format("Y-m-d_H:i:s");

        $backupPath .= "/{$host->getHostId()}/$project/profiles/";

        if (!is_dir($backupPath)) {
            mkdir($backupPath, 0777, true);
        }

        $backupPath .= "$name.zip";

        $res = $zip->open($backupPath, \ZipArchive::CREATE);

        foreach ($profiles as $name => $profile) {
            $yaml = Yaml::dump($profile, 10, 2, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK);
            $zip->addFromString("{$profile["name"]}.yaml", (string) $yaml);
        }

        $zip->close();

        $this->insertProfilesBackup->insert(
            $host->getHostId(),
            $project,
            $backupPath,
            filesize($backupPath)
        );
    }
}

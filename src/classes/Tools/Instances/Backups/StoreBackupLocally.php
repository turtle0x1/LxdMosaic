<?php

namespace dhope0000\LXDClient\Tools\Instances\Backups;

use dhope0000\LXDClient\Model\InstanceSettings\GetSetting;
use dhope0000\LXDClient\Constants\InstanceSettingsKeys;
use dhope0000\LXDClient\Model\Hosts\Backups\Instances\InsertInstanceBackup;
use dhope0000\LXDClient\Tools\Instances\Backups\DeleteRemoteBackup;

use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use dhope0000\LXDClient\Tools\Hosts\HasExtension;
use dhope0000\LXDClient\Constants\LxdApiExtensions;

use dhope0000\LXDClient\Objects\Host;

class StoreBackupLocally
{
    private $getSetting;
    private $filesystem;
    private $insertInstanceBackup;
    private $deleteRemoteBackup;
    private $hasExtension;

    public function __construct(
        GetSetting $getSetting,
        Filesystem $filesystem,
        InsertInstanceBackup $insertInstanceBackup,
        DeleteRemoteBackup $deleteRemoteBackup,
        HasExtension $hasExtension
    ) {
        $this->hasExtension = $hasExtension;
        $this->getSetting = $getSetting;
        $this->filesystem = $filesystem;
        $this->insertInstanceBackup = $insertInstanceBackup;
        $this->deleteRemoteBackup = $deleteRemoteBackup;
    }

    public function store(Host $host, string $instance, string $backup, bool $deleteRemote)
    {
        $hostId = $host->getHostId();
        if ($this->hasExtension->checkWithHost($host, LxdApiExtensions::CONTAINER_BACKUP) !== true) {
            throw new \Exception("Host doesn't support backups", 1);
        }

        $backupDir = $this->getSetting->getSettingLatestValue(InstanceSettingsKeys::BACKUP_DIRECTORY);
        $backupDir = $this->makeDirectory($backupDir, $hostId, $instance);

        $backupInfo = $this->downloadBackup($host, $backupDir, $instance, $backup);

        $this->insertInstanceBackup->insert(
            (new \DateTime($backupInfo["created"])),
            $hostId,
            $instance,
            $backup,
            $backupInfo["backupFile"]
        );

        if ($deleteRemote) {
            $this->deleteRemoteBackup->delete($host, $instance, $backup);
        }

        return true;
    }

    private function downloadBackup(
        Host $host,
        string $backupDir,
        string $instance,
        string $backup
    ) :array {
        $backupInfo = $host->instances->backups->info($instance, $backup);

        $backupFileName = "backup." . $backupInfo['created_at'] .".tar.gz";

        $backupFilePath = "$backupDir/$backupFileName";

        $this->filesystem->touch($backupFilePath);

        $this->filesystem->appendToFile($backupFilePath, $host->instances->backups->export($instance, $backup));

        return [
            "backupFile"=>$backupFilePath,
            "created"=>$backupInfo["created_at"]
        ];
    }

    private function makeDirectory(string $backupDir, int $hostId, string $instance) :string
    {
        try {
            $this->filesystem = new Filesystem();
            $path = "$backupDir/$hostId/$instance";

            if ($this->filesystem->exists($path)) {
                return $path;
            }

            $this->filesystem->mkdir($path);

            return $path;
        } catch (IOExceptionInterface $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}

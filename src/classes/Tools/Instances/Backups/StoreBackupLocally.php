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

use dhope0000\LXDClient\Tools\Instances\Backups\DownloadFile;

class StoreBackupLocally
{
    private GetSetting $getSetting;
    private Filesystem $filesystem;
    private InsertInstanceBackup $insertInstanceBackup;
    private DeleteRemoteBackup $deleteRemoteBackup;
    private HasExtension $hasExtension;
    private DownloadFile $downloadFile;

    public function __construct(
        GetSetting $getSetting,
        Filesystem $filesystem,
        InsertInstanceBackup $insertInstanceBackup,
        DeleteRemoteBackup $deleteRemoteBackup,
        HasExtension $hasExtension,
        DownloadFile $downloadFile
    ) {
        $this->hasExtension = $hasExtension;
        $this->getSetting = $getSetting;
        $this->filesystem = $filesystem;
        $this->insertInstanceBackup = $insertInstanceBackup;
        $this->deleteRemoteBackup = $deleteRemoteBackup;
        $this->downloadFile = $downloadFile;
    }

    public function store(Host $host, string $project, string $instance, string $backup, bool $deleteRemote)
    {
        set_time_limit(0);

        $hostId = $host->getHostId();
        if ($this->hasExtension->checkWithHost($host, LxdApiExtensions::CONTAINER_BACKUP) !== true) {
            throw new \Exception("Host doesn't support backups", 1);
        }

        if (isset($_ENV["SNAP"])) {
            $backupDir = $_ENV["SNAP_COMMON"];
        } else {
            $backupDir = $this->getSetting->getSettingLatestValue(InstanceSettingsKeys::BACKUP_DIRECTORY);
        }

        $backupDir = $this->makeDirectory($backupDir, $hostId, $project, $instance);

        $backupInfo = $this->downloadBackup($host, $project, $instance, $backupDir, $backup);

        $backupDate = (new \DateTime($backupInfo["created"]))->setTimezone(new \DateTimeZone("UTC"));

        $this->insertInstanceBackup->insert(
            $backupDate,
            $hostId,
            $project,
            $instance,
            $backup,
            $backupInfo["backupFile"],
            $backupInfo["filesize"]
        );

        if ($deleteRemote) {
            $this->deleteRemoteBackup->delete($host, $instance, $backup);
        }

        return true;
    }

    private function downloadBackup(
        Host $host,
        string $project,
        string $instance,
        string $backupDir,
        string $backup
    ) :array {
        $backupInfo = $host->instances->backups->info($instance, $backup);

        $backupFileName = "backup." . $backupInfo['created_at'] .".tar";

        $backupFilePath = "$backupDir/$backupFileName";

        $this->downloadFile->download($host, $project, $instance, $backupFilePath, $backup);

        return [
            "filesize"=>filesize($backupFilePath),
            "backupFile"=>$backupFilePath,
            "created"=>$backupInfo["created_at"]
        ];
    }

    private function makeDirectory(string $backupDir, int $hostId, string $project, string $instance) :string
    {
        try {
            $this->filesystem = new Filesystem();
            $path = "$backupDir/$hostId/$project/$instance";

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

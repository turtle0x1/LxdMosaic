<?php

namespace dhope0000\LXDClient\Tools\Containers\Backups;

use dhope0000\LXDClient\Model\InstanceSettings\GetSetting;
use dhope0000\LXDClient\Constants\InstanceSettingsKeys;
use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Model\Hosts\Backups\Containers\InsertContainerBackup;
use dhope0000\LXDClient\Tools\Containers\Backups\DeleteRemoteBackup;

use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

class StoreBackupLocally
{
    private $getSetting;
    private $lxdClient;
    private $filesystem;
    private $insertContainerBackup;
    private $deleteRemoteBackup;

    public function __construct(
        GetSetting $getSetting,
        LxdClient $lxdClient,
        Filesystem $filesystem,
        InsertContainerBackup $insertContainerBackup,
        DeleteRemoteBackup $deleteRemoteBackup
    ) {
        $this->getSetting = $getSetting;
        $this->lxdClient = $lxdClient;
        $this->filesystem = $filesystem;
        $this->insertContainerBackup = $insertContainerBackup;
        $this->deleteRemoteBackup = $deleteRemoteBackup;
    }

    public function store(int $hostId, string $container, string $backup, bool $deleteRemote)
    {
        $backupDir = $this->getSetting->getSettingLatestValue(InstanceSettingsKeys::BACKUP_DIRECTORY);
        $backupDir = $this->makeDirectory($backupDir, $hostId, $container);

        $backupInfo = $this->downloadBackup($backupDir, $hostId, $container, $backup);

        $this->insertContainerBackup->insert(
            (new \DateTime($backupInfo["created"])),
            $hostId,
            $container,
            $backup,
            $backupInfo["backupFile"]
        );

        if ($deleteRemote) {
            $this->deleteRemoteBackup->delete($hostId, $container, $backup);
        }

        return true;
    }

    private function downloadBackup(
        string $backupDir,
        int $hostId,
        string $container,
        string $backup
    ) :array {
        $client = $this->lxdClient->getANewClient($hostId);

        $backupInfo = $client->containers->backups->info($container, $backup);

        $backupFileName = "backup." . $backupInfo['created_at'] .".tar.gz";

        $backupFilePath = "$backupDir/$backupFileName";

        $this->filesystem->touch($backupFilePath);

        $this->filesystem->appendToFile($backupFilePath, $client->containers->backups->export($container, $backup));

        return [
            "backupFile"=>$backupFilePath,
            "created"=>$backupInfo["created_at"]
        ];
    }

    private function makeDirectory(string $backupDir, int $hostId, string $container) :string
    {
        try {
            $this->filesystem = new Filesystem();
            $path = "$backupDir/$hostId/$container";

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

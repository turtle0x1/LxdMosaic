<?php

namespace dhope0000\LXDClient\Controllers\Instances\Backups;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\Backups\StoreBackupLocally;
use Symfony\Component\Routing\Annotation\Route;

class ImportBackupController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly StoreBackupLocally $storeBackupLocally
    ) {
    }

    /**
     * @Route("/api/Instances/Backups/ImportBackupController/import", name="Import Remote Instance Backup", methods={"POST"})
     */
    public function import(Host $host, string $container, string $backup, int $delete)
    {
        $this->storeBackupLocally->store(
            $host,
            $host->callClientMethod('getProject'),
            $container,
            $backup,
            (bool) $delete
        );

        return [
            'state' => 'success',
            'message' => 'Imported backup',
        ];
    }
}

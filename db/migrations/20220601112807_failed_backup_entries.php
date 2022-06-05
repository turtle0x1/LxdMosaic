<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class FailedBackupEntries extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('Container_Backups');
        $table->addColumn('CB_Failed', 'boolean', ['after' => 'CB_Deleted'])
              ->addColumn('CB_Failed_Reason', 'text')
              ->update();
    }
}

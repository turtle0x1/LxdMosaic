<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class StoreBackupProject extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        // create the table
        $table = $this->table('Container_Backups');
        $table->addColumn('CB_Project', 'string')
            ->save();
        if ($this->isMigratingUp()) {
            // Some testing shows backups can only be default because the
            // software was bugged
            $this->execute("UPDATE `Container_Backups` SET `CB_Project` = 'default'");
        }
    }
}

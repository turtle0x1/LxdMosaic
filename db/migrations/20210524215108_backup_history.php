<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class BackupHistory extends AbstractMigration
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
        if ($this->isMigratingUp()) {
            // Add new setting
            $this->execute("INSERT INTO `Instance_Settings`(`IS_ID`, `IS_Name`) VALUES (12, 'Backup History')");
            // Enforce strong passwords by default
            $this->execute("INSERT INTO `Instance_Settings_Values`(`ISV_IS_ID`, `ISV_Value`) VALUES (12, '-1 year')");
        }
    }
}

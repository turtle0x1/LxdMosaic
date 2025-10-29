<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class TimerSnapshots extends AbstractMigration
{
    public function change(): void
    {
        if ($this->isMigratingUp()) {
            $this->execute("INSERT INTO `Instance_Settings`(`IS_ID`, `IS_Name`) VALUES (16, 'Timers Snapshot')");
            $this->execute("INSERT INTO `Instance_Settings`(`IS_ID`, `IS_Name`) VALUES (17, 'Timers Snapshot Days Duration')");

            $this->execute("INSERT INTO `Instance_Settings_Values`(`ISV_IS_ID`, `ISV_Value`) VALUES (16, '0')");
            $this->execute("INSERT INTO `Instance_Settings_Values`(`ISV_IS_ID`, `ISV_Value`) VALUES (17, '7')");

            $this->table('Timers_Snapshots', ['id' => "TS_ID", 'primary_key' => ["TS_ID"]])
                ->addColumn('TS_Last_Updated', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
                ->addColumn('TS_Date', 'date', ['null' => false])
                ->addColumn('TS_Data', 'json', ['null' => false])
                ->addIndex(['TS_Date'], ['unique' => true,  'name' => 'unique_timers_monitor'])
                ->create();
        }
    }
}

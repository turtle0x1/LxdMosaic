<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class SoftwareInventory extends AbstractMigration
{
    public function change(): void
    {
        if ($this->isMigratingUp()) {
            // Add new settings
            $this->execute("INSERT INTO `Instance_Settings`(`IS_ID`, `IS_Name`) VALUES (14, 'Software Assets Snapshot')");
            $this->execute("INSERT INTO `Instance_Settings`(`IS_ID`, `IS_Name`) VALUES (15, 'Software Assets Snapshot Days Duration')");

            // Dont enable software asset monitoring by default & clear software asset management monitoring logs after 7 days
            $this->execute("INSERT INTO `Instance_Settings_Values`(`ISV_IS_ID`, `ISV_Value`) VALUES (14, '0')");
            $this->execute("INSERT INTO `Instance_Settings_Values`(`ISV_IS_ID`, `ISV_Value`) VALUES (15, '7')");

            // Create table to store software assets monitor results
            $this->table('Software_Assets_Snapshots', ['id' => "SAS_ID", 'primary_key' => ["SAS_ID"]])
                ->addColumn('SAS_Last_Updated', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
                ->addColumn('SAS_Date', 'date', ['null' => false])
                ->addColumn('SAS_Data', 'json', ['null' => false])
                ->addIndex(['SAS_Date'], ['unique' => true,  'name' => 'unique_software_assets_monitor'])
                ->create();
        }
    }
}

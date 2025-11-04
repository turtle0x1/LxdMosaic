<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use dhope0000\LXDClient\Constants\InstanceSettingsKeys;

final class SearchIndex extends AbstractMigration
{
    public function change(): void
    {
        if ($this->isMigratingUp()) {
            $this->execute("INSERT INTO `Instance_Settings`(`IS_ID`, `IS_Name`) VALUES (" . InstanceSettingsKeys::SEARCH_INDEX . ", 'Search Index')");
            $this->execute("INSERT INTO `Instance_Settings_Values`(`ISV_IS_ID`, `ISV_Value`) VALUES (" . InstanceSettingsKeys::SEARCH_INDEX . ", '0')");

            $this->table('Search_Index', ['id' => "SI_ID", 'primary_key' => ["SI_ID"]])
                ->addColumn('SI_Last_Updated', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
                ->addColumn('SI_Data', 'json', ['null' => false])
                ->create();
        }
    }
}

<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class SiteTitleSetting extends AbstractMigration
{
    public function change(): void
    {
        if ($this->isMigratingUp()) {
            // Add new setting
            $this->execute("INSERT INTO `Instance_Settings`(`IS_ID`, `IS_Name`) VALUES (10, 'Site Title')");
            // Enforce strong passwords by default
            $this->execute("INSERT INTO `Instance_Settings_Values`(`ISV_IS_ID`, `ISV_Value`) VALUES (10, 'LXD Mosaic')");
        }
    }
}

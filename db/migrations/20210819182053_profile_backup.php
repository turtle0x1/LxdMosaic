<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ProfileBackup extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('Profile_Backup_Strategies', ['id' => "PBS_ID", 'primary_key' => ["PBS_ID"]]);
        $table->addColumn('PBS_Date_Created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('PBS_Name', 'string')
            ->create();

        $this->execute("INSERT INTO `Profile_Backup_Strategies`(`PBS_Name`) VALUES ('Download & Archive')");

        $table = $this->table('Profile_Backup_Schedule', ['id' => "PBS_ID", 'primary_key' => ["PBS_ID"]]);
        $table->addColumn('PBS_Date_Created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('PBS_User_ID', 'integer')
            ->addColumn('PBS_Schedule_String', 'string')
            ->addColumn('PBS_Retention', 'integer')
            ->addColumn('PBS_Host_ID', 'integer')
            ->addColumn('PBS_Project', 'string')
            ->addColumn('PBS_Stratergy_ID', 'integer')
            ->addColumn('PBS_Disabled', 'boolean', ['null'=>false, "default"=>0])
            ->addColumn('PBS_Disabled_Date', 'datetime', ['null'=>true])
            ->addColumn('PBS_Disabled_By', 'integer', ['null'=>true])
            ->addForeignKey('PBS_User_ID', 'Users', 'User_ID', ['delete'=> 'RESTRICT', 'update'=> 'RESTRICT'])
            ->addForeignKey('PBS_Stratergy_ID', 'Profile_Backup_Strategies', 'PBS_ID', ['delete'=> 'RESTRICT', 'update'=> 'RESTRICT'])
            ->addForeignKey('PBS_Disabled_By', 'Users', 'User_ID', ['delete'=> 'RESTRICT', 'update'=> 'RESTRICT'])
            ->addForeignKey('PBS_Host_ID', 'Hosts', 'Host_ID', ['delete'=> 'CASCADE', 'update'=> 'RESTRICT'])
            ->create();

        $table = $this->table('Profile_Backups', ['id' => "PB_ID", 'primary_key' => ["PB_ID"]]);
        $table->addColumn('PB_Date_Created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('PB_Host_ID', 'integer')
            ->addColumn('PB_Local_Path', 'string')
            ->addColumn('PB_Project', 'string')
            ->addColumn('PB_Filesize', 'biginteger')
            ->addColumn('PB_Deleted', 'datetime', ['null'=>true])
            ->addForeignKey('PB_Host_ID', 'Hosts', 'Host_ID', ['delete'=> 'RESTRICT', 'update'=> 'RESTRICT'])
            ->create();
    }
}

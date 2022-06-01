<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class DeleteUser extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('Users');
        $table->addColumn('User_Deleted', 'boolean', ['after' => 'User_Login_Disabled', "default"=>0])
              ->addColumn('User_Deleted_Date', 'datetime', ["null"=>true, "default"=>null])
              ->addColumn('User_Deleted_By', 'integer', ['null'=>true, "default"=>null])
              ->addForeignKey('User_Deleted_By', 'Users', 'User_ID', ['delete'=> 'RESTRICT', 'update'=> 'RESTRICT'])
              ->update();
    }
}

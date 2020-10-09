<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UserProjectLimit extends AbstractMigration
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
    public function up(): void
    {
        // create the table
        $table = $this->table('User_Allowed_Projects', ['id' => "UAP_ID", 'primary_key' => ["UAP_ID"]]);
        $table->addColumn('UAP_Date_Created', 'datetime')
              ->addColumn('UAP_Granted_By', 'integer')
              ->addColumn('UAP_User_ID', 'integer')
              ->addColumn('UAP_Host_ID', 'integer')
              ->addColumn('UAP_Project', 'string')
              ->create();
    }
}

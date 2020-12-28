<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class LdapSupport extends AbstractMigration
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
        $table = $this->table('Users');
        $table->addColumn('User_Ldap_ID', 'string', ['null'=>true])
            ->update();


        $rows = [
            [
                'IS_ID'    => 4,
                'IS_Name'  => 'Ldap Server',
            ],
            [
                'IS_ID'    => 5,
                'IS_Name'  => 'Ldap Lookup User DN',
            ],
            [
                'IS_ID'    => 6,
                'IS_Name'  => 'Ldap Lookup User Password',
            ],
            [
                'IS_ID'    => 7,
                'IS_Name'  => 'Ldap Base DN',
            ]
        ];

        $this->table('Instance_Settings')->insert($rows)->save();
    }
}

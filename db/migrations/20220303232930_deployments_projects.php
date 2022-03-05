<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class DeploymentsProjects extends AbstractMigration
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
        $table = $this->table('Deployment_Projects', ['id' => "DP_ID", 'primary_key' => ["DP_ID"]]);
        $table->addColumn('DP_Date_Created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('DP_User_ID', 'integer')
            ->addColumn('DP_Deployment_ID', 'integer')
            ->addColumn('DP_Host_ID', 'integer')
            ->addColumn('DP_Project', 'string')
            ->addForeignKey('DP_User_ID', 'Users', 'User_ID', ['delete'=> 'RESTRICT', 'update'=> 'RESTRICT'])
            ->addForeignKey('DP_Deployment_ID', 'Deployments', 'Deployment_ID', ['delete'=> 'CASCADE', 'update'=> 'RESTRICT'])
            ->addForeignKey('DP_Host_ID', 'Hosts', 'Host_ID', ['delete'=> 'CASCADE', 'update'=> 'RESTRICT'])
            ->addIndex([
                'DP_Deployment_ID',
                'DP_Host_ID',
                'DP_Project'
            ], ['unique' => true,  'name' => 'unique_deployment_project'])
            ->create();

        if ($this->isMigratingUp()) {
            $this->execute("INSERT INTO `Deployment_Projects`(
                `DP_User_ID`,
                `DP_Deployment_ID`,
                `DP_Host_ID`,
                `DP_Project`
            ) SELECT
                (SELECT `User_ID` FROM `Users` WHERE `User_Admin` = 1 ORDER BY `User_Date_Created` ASC LIMIT 1),
                `Deployment_ID`,
                (SELECT `Host_ID` FROM `Hosts` ORDER BY `Host_ID` ASC LIMIT 1),
                'default'
            FROM
                `Deployments`
            ");
        }
    }
}

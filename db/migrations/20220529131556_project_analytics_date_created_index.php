<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ProjectAnalyticsDateCreatedIndex extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('Project_Analytics');
        $table->addIndex(['PA_Date_Created'])
                ->save();
    }
}

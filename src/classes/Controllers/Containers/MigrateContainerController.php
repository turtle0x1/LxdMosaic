<?php
namespace dhope0000\LXDClient\Controllers\Containers;

use dhope0000\LXDClient\Tools\Containers\MigrateContainer;

class MigrateContainerController
{
    public function __construct(MigrateContainer $migrateContainer)
    {
        $this->migrateContainer = $migrateContainer;
    }

    public function migrateContainer($host, $container, $destination)
    {
        $this->migrateContainer->migrate($host, $container, $destination);
        return array("success"=>"Container Has Been Migrated");
    }
}

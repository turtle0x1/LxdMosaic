<?php
namespace dhope0000\LXDClient\Controllers\Instances;

use dhope0000\LXDClient\Tools\Instances\Migrate;

class MigrateInstanceController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(Migrate $migrate)
    {
        $this->migrate = $migrate;
    }

    public function migrate($hostId, $container, $destination)
    {
        $this->migrate->migrate($hostId, $container, $destination);
        return array("success"=>"Instance Has Been Migrated");
    }
}

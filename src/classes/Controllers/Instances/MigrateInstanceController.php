<?php
namespace dhope0000\LXDClient\Controllers\Instances;

use dhope0000\LXDClient\Tools\Instances\Migrate;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class MigrateInstanceController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(Migrate $migrate)
    {
        $this->migrate = $migrate;
    }
    /**
     * @Route("/api/Instances/MigrateInstanceController/migrate", methods={"POST"}, name="Migrate Instance", options={"rbac" = "instances.migrate"})
     */
    public function migrate(Host $hostId, $container, Host $destination)
    {
        $this->migrate->migrate($hostId, $container, $destination, $container, true);
        return array("success"=>"Instance Has Been Migrated");
    }
}

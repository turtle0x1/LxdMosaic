<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class RestoreBackupControllerTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");


        $this->database = $container->get("dhope0000\LXDClient\Model\Database\Database");
        $this->database->dbObject->beginTransaction();
        $inesrtBackup = $container->make("dhope0000\LXDClient\Model\Hosts\Backups\Instances\InsertInstanceBackup");
        $this->backupId = $inesrtBackup->insert(
            new \DateTime(),
            1,
            "default",
            "fakeInstance",
            "fakeBackupName",
            "/not/a/real/path"
        );
    }

    public function tearDown() :void
    {
        $this->database->dbObject->rollBack();
    }


    public function test_no_acces_to_project_doesnt_allow_restore() :void
    {
        $this->expectException(\Exception::class);
        $_POST = ["backupId"=>$this->backupId, "targetHost"=>1];


        $result = $this->routeApi->route(
            array_filter(explode('/', '/Backups/RestoreBackupController/restore')),
            ["userid"=>2, "project"=>"testProject"],
            true
        );
    }
}

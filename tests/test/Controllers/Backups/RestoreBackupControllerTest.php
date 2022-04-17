<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;

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
            "/not/a/real/path",
            0
        );
    }

    public function tearDown() :void
    {
        $this->database->dbObject->rollBack();
    }

    /**
     * TODO Appears useless, just testing user doesnt have access to project
     */
    public function test_no_acces_to_project_doesnt_allow_restore() :void
    {
        $this->expectException(\Exception::class);
        $request =  new Request();
        $request = $request->create(
            "api/Backups/RestoreBackupController/restore",
            "POST",
            ["backupId"=>$this->backupId, "targetHost"=>1],
            [],
            [],
            ["HTTP_USERID"=>2, "HTTP_APITOKEN"=>"FAKE", "HTTP_PROJECT"=>"testProject"],
        );
        $context = new RequestContext();
        $context->fromRequest($request);
        $this->routeApi->route(
            $request,
            $context,
            true
        );
    }
}

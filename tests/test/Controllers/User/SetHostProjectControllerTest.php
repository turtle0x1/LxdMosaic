<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;

final class SetHostProjectControllerTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");

        $this->database = $container->get("dhope0000\LXDClient\Model\Database\Database");
        $this->database->dbObject->beginTransaction();

        $addHost = $container->make("dhope0000\LXDClient\Model\Hosts\AddHost");
        $addHost->addHost("localhostTwo", "fake", "fake", "fake", "localHostTwo");
        $this->newHostId = $addHost->getId();
    }

    public function tearDown() :void
    {
        $this->database->dbObject->rollBack();
    }

    public function test_userTryingToSetProjectOnAHostWithNoAccess() :void
    {
        $this->expectException(\Exception::class);
        $request =  new Request();
        $request = $request->create(
            "api/User/SetHostProjectController/set",
            "POST",
            ["hostId"=>$this->newHostId, "project"=>"default"],
            [],
            [],
            ["HTTP_USERID"=>2],
        );
        $context = new RequestContext();
        $context->fromRequest($request);

        $result = $this->routeApi->route(
            $request,
            $context,
            true
        );
    }

    public function test_userTryingToSetToProjectWithNoAcess() :void
    {
        $this->expectException(\Exception::class);
        $request =  new Request();
        $request = $request->create(
            "api/User/SetHostProjectController/set",
            "POST",
            ["hostId"=>1, "project"=>"default"],
            [],
            [],
            ["HTTP_USERID"=>2],
        );
        $context = new RequestContext();
        $context->fromRequest($request);

        $result = $this->routeApi->route(
            $request,
            $context,
            true
        );
    }

    public function test_userChangesProject() :void
    {
        $request =  new Request();
        $request = $request->create(
            "api/User/SetHostProjectController/set",
            "POST",
            ["hostId"=>1, "project"=>"testProject"],
            [],
            [],
            ["HTTP_USERID"=>1],
        );
        $context = new RequestContext();
        $context->fromRequest($request);

        $result = $this->routeApi->route(
            $request,
            $context,
            true
        );

        $this->assertEquals(["state"=>"success", "message"=>"Changed project to testProject"], $result);
    }
}

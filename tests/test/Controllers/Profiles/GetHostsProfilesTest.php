<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;

final class GetHostsProfilesTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    public function test_getHostsProjects() :void
    {
        $request =  new Request();
        $request = $request->create(
            "api/Profiles/GetAllProfilesController/getAllProfiles",
            "POST",
            [],
            [],
            [],
            ["HTTP_USERID"=>1, "HTTP_APITOKEN"=>"fakeToken"],
        );
        $context = new RequestContext();
        $context->fromRequest($request);

        $result = $this->routeApi->route(
            $request,
            $context,
            true
        );

        $this->assertEquals(["clusters", "standalone"], array_keys($result));

        $host = json_decode(json_encode($result["standalone"]["members"][0]), true);
        $hostKeys = array_keys($host);

        $this->assertEquals([
            'hostId',
            'alias',
            'urlAndPort',
            'hostOnline',
            'supportsLoadAvgs',
            'currentProject',
            'profiles'
        ], $hostKeys);

        $this->assertEquals([
            "default",
            "testProfile"
        ], $host["profiles"]);
    }
}

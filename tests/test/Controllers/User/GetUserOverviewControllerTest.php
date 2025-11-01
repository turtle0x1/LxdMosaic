<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class GetUserOverviewControllerTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    public function test_non_admin_cant_access_user_recorded_actions() :void
    {
        $this->expectException(\Exception::class);
        $_POST = ["targetUser"=>1];

        $result = $this->routeApi->route(
            Request::create('/api/User/GetUserOverviewController/get', 'POST'),
            ["userid"=>2],
            true
        );
    }
}

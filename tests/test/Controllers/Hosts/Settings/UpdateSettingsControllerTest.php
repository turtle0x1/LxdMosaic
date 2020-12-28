<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class UpdateSettingsControllerTest extends TestCase
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
        $addHost->addHost("localhostTwo", "fake", "fake", "fake", "duplicateAlias");
    }

    public function tearDown() :void
    {
        $this->database->dbObject->rollBack();
    }

    public function test_nonAdminCantUpdateHostsettings() :void
    {
        $this->expectException(\Exception::class);

        $_POST = ["hostId"=>1, "alias"=>"test", "supportsLoadAverages"=>0];

        $this->routeApi->route(
            array_filter(explode('/', '/Hosts/Settings/UpdateSettingsController/update')),
            ["userid"=>2],
            true
        );
    }

    public function test_adminCanUpdateHostSettings() :void
    {
        $_POST = ["hostId"=>1, "alias"=>"test", "supportsLoadAverages"=>0];

        $result = $this->routeApi->route(
            array_filter(explode('/', '/Hosts/Settings/UpdateSettingsController/update')),
            ["userid"=>1],
            true
        );

        $this->assertEquals(["state"=>"success", "messages"=>"Updated Settings"], $result);
    }


    public function test_duplicateAliasThrowsException() :void
    {
        $this->expectException(\Exception::class);

        $_POST = ["hostId"=>1, "alias"=>"duplicateAlias", "supportsLoadAverages"=>0];

        $this->routeApi->route(
            array_filter(explode('/', '/Hosts/Settings/UpdateSettingsController/update')),
            ["userid"=>1],
            true
        );
    }
}

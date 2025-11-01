<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use dhope0000\LXDClient\Constants\InstanceSettingsKeys;

final class SaveAllSettingsControllerTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");

        $this->database = $container->get("dhope0000\LXDClient\Model\Database\Database");
        $this->database->dbObject->beginTransaction();
    }

    public function tearDown() :void
    {
        $this->database->dbObject->rollBack();
    }

    public function test_nonAdminCantSaveSettings() :void
    {
        $this->expectException(\Exception::class);

        $_POST = ["settings"=>[]];

        $this->routeApi->route(
            Request::create('/api/InstanceSettings/SaveAllSettingsController/saveAll', 'POST'),
            ["userid"=>2],
            true
        );
    }

    public function test_adminCanSaveSettings() :void
    {
        $_POST = ["settings"=>[["id"=>InstanceSettingsKeys::STRONG_PASSWORD_POLICY, "value"=>0]]];

        $result = $this->routeApi->route(
            Request::create('/api/InstanceSettings/SaveAllSettingsController/saveAll', 'POST'),
            ["userid"=>1],
            true
        );

        $this->assertEquals(["state"=>"success", "message"=>"Saved Settings"], $result);
    }
}

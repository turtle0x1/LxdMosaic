<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use dhope0000\LXDClient\Constants\InstanceSettingsKeys;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;

final class SaveLdapSettingsControllerTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    public function test_nonAdminCantSaveSettings() :void
    {
        $this->expectException(\Exception::class);
        $request =  new Request();
        $request = $request->create(
            "api/InstanceSettings/Ldap/SaveLdapSettingsController/save",
            "POST",
            ["settings"=>[]],
            [],
            [],
            ["HTTP_USERID"=>2],
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

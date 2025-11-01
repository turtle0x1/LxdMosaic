<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class DeleteImagesControllerTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    public function test_no_access_doesnt_allow_deleting_images() :void
    {
        $this->expectException(\Exception::class);
        $_POST = [
            "imageData"=>[
                ["hostId"=>2, "fingerprint"=>"fakeFingerPrint"]
            ]
        ];


        $result = $this->routeApi->route(
            Request::create('/api/Images/DeleteImagesController/delete', 'POST'),
            ["userid"=>2, "project"=>"testProject"],
            true
        );
    }
}

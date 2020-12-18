<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

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
            array_filter(explode('/', '/Images/DeleteImagesController/delete')),
            ["userid"=>2, "project"=>"testProject"],
            true
        );
    }
}

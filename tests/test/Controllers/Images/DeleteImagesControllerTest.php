<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class DeleteImagesControllerTest extends TestCase
{
    private $routeApi;
    #[\Override]
    protected function setUp(): void
    {
        $container = (new \DI\ContainerBuilder)->useAttributes(true)->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    public function testNoAccessDoesntAllowDeletingImages(): void
    {
        $this->expectException(\Exception::class);
        $_POST = [
            'imageData' => [
                [
                    'hostId' => 2,
                    'fingerprint' => 'fakeFingerPrint',
                ],
            ],
        ];

        $result = $this->routeApi->route(
            Request::create('/api/Images/DeleteImagesController/delete', 'POST'),
            [
                'userid' => 2,
                'project' => 'testProject',
            ],
            true
        );
    }
}

<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class CreateProfileTest extends TestCase
{
    #[\Override]
    protected function setUp(): void
    {
        $container = (new \DI\ContainerBuilder)->useAttributes(true)->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    /**
     * @dataProvider data_createProjectData
     */
    public function testCreateProfile($data, $expected): void
    {
        $_POST = $data;

        $result = $this->routeApi->route(
            Request::create('/api/Profiles/CreateProfileController/create', 'POST'),
            [
                'userid' => 1,
            ],
            true
        );

        $this->assertEquals($expected, $result);
    }

    public function data_createProjectData()
    {
        return [
            [
                [
                    'hosts' => [1],
                    'name' => 'testProfile',
                    'description' => 'testDescription',
                ],
                [
                    'state' => 'success',
                    'message' => 'Created Profiles',
                ],
            ],
        ];
    }
}

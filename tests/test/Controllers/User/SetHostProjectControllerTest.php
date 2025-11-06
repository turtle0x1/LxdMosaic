<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class SetHostProjectControllerTest extends TestCase
{
    #[\Override]
    protected function setUp(): void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");

        $this->database = $container->get("dhope0000\LXDClient\Model\Database\Database");
        $this->database->dbObject->beginTransaction();

        $addHost = $container->make("dhope0000\LXDClient\Model\Hosts\AddHost");
        $addHost->addHost('localhostTwo', 'fake', 'fake', 'fake', 'localHostTwo');
        $this->newHostId = $addHost->getId();
    }

    #[\Override]
    protected function tearDown(): void
    {
        $this->database->dbObject->rollBack();
    }

    public function testUserTryingToSetProjectOnAHostWithNoAccess(): void
    {
        $this->expectException(\Exception::class);

        $_POST = [
            'hostId' => $this->newHostId,
            'project' => 'default',
        ];

        $this->routeApi->route(
            Request::create('/api/User/SetHostProjectController/set', 'POST'),
            [
                'userid' => 2,
            ],
            true
        );
    }

    public function testUserTryingToSetToProjectWithNoAcess(): void
    {
        $this->expectException(\Exception::class);

        $_POST = [
            'hostId' => 1,
            'project' => 'default',
        ];

        $this->routeApi->route(
            Request::create('/api/User/SetHostProjectController/set', 'POST'),
            [
                'userid' => 2,
            ],
            true
        );
    }

    public function testUserChangesProject(): void
    {
        $_POST = [
            'hostId' => 1,
            'project' => 'testProject',
        ];

        $result = $this->routeApi->route(
            Request::create('/api/User/SetHostProjectController/set', 'POST'),
            [
                'userid' => 2,
            ],
            true
        );

        $this->assertEquals([
            'state' => 'success',
            'message' => 'Changed project to testProject',
        ], $result);
    }
}

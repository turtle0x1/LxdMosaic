<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class SearchHostsControllerTest extends TestCase
{
    private $routeApi;
    private $database;
    #[\Override]
    protected function setUp(): void
    {
        $container = (new \DI\ContainerBuilder)->useAttributes(true)->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");

        $this->database = $container->get("dhope0000\LXDClient\Model\Database\Database");
        $this->database->dbObject->beginTransaction();
        $addHost = $container->make("dhope0000\LXDClient\Model\Hosts\AddHost");
        $addHost->addHost('localhostTwo', 'fake', 'fake', 'fake', 'localHostTwo');
    }

    #[\Override]
    protected function tearDown(): void
    {
        $this->database->dbObject->rollBack();
    }

    public function testAccessToSearchLimitedHosts(): void
    {
        $_POST = [
            'hostSearch' => 'localhost',
        ];

        $result = $this->routeApi->route(
            Request::create('/api/Hosts/SearchHosts/search', 'POST'),
            [
                'userid' => 2,
            ],
            true
        );

        $this->assertTrue(count($result) === 1);
    }

    public function testAccessToSearchAllHosts(): void
    {
        $_POST = [
            'hostSearch' => 'localhost',
        ];

        $result = $this->routeApi->route(
            Request::create('/api/Hosts/SearchHosts/search', 'POST'),
            [
                'userid' => 1,
            ],
            true
        );

        $this->assertTrue(count($result) === 2);
    }
}

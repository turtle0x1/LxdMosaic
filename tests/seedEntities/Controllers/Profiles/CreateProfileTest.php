<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class CreateProfileTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }
    /**
     * @dataProvider data_createProjectData
     */
    public function test_createProfile($data, $expected) :void
    {
        $_POST = $data;

        $result = $this->routeApi->route(
            Request::create('/api/Profiles/CreateProfileController/create', 'POST'),
            ["userid"=>1],
            true
        );

        $this->assertEquals($expected, $result);
    }

    public function data_createProjectData()
    {
        return [
            [
                [
                    "hosts"=>[1],
                    "name"=>"testProfile",
                    "description"=>"testDescription"
                ],
                [
                    "state"=>"success", "message"=>"Created Profiles"
                ]
            ]
        ];
    }
}

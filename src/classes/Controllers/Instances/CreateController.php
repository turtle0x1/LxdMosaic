<?php

namespace dhope0000\LXDClient\Controllers\Instances;

use dhope0000\LXDClient\Tools\Instances\CreateInstance;
use dhope0000\LXDClient\Constants\LxdInstanceTypes;
use dhope0000\LXDClient\Objects\HostsCollection;
use Symfony\Component\Routing\Annotation\Route;

class CreateController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private CreateInstance $createInstance;

    public function __construct(CreateInstance $createInstance)
    {
        $this->createInstance = $createInstance;
    }
    /**
     * @Route("", name="Create Instance")
     */
    public function create(
        string $name,
        array $profileIds = [],
        HostsCollection $hosts,
        array $imageDetails,
        string $instanceType = "",
        string $server = "",
        array $gpus = [],
        array $config = [],
        bool $start = false
    ) {
        $lxdResponses = $this->createInstance->create(
            LxdInstanceTypes::CONTAINER,
            $name,
            $profileIds,
            $hosts,
            $imageDetails,
            $server,
            $instanceType,
            $gpus,
            $config,
            $start
        );
        return ["success"=>"Created Container", "lxdResponses"=>$lxdResponses];
    }
}

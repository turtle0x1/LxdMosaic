<?php

namespace dhope0000\LXDClient\Controllers\Instances;

use dhope0000\LXDClient\Tools\Instances\CreateInstance;
use dhope0000\LXDClient\Constants\LxdInstanceTypes;
use dhope0000\LXDClient\Objects\HostsCollection;

class CreateController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(CreateInstance $createInstance)
    {
        $this->createInstance = $createInstance;
    }

    public function create(
        $name,
        $profileIds = [],
        HostsCollection $hosts,
        array $imageDetails,
        string $instanceType = "",
        $server = "",
        array $gpus = [],
        array $config = []
    ) {
        $lxdResponses = $this->createInstance->create(
            LxdInstanceTypes::CONTAINER,
            $name,
            $profileIds,
            $hosts,
            $imageDetails,
            $server,
            [],
            $instanceType,
            $gpus,
            $config
        );
        return ["success"=>"Created Container", "lxdResponses"=>$lxdResponses];
    }
}

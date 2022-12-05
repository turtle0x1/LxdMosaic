<?php

namespace dhope0000\LXDClient\Tools\Instances\InstanceTypes;

use dhope0000\LXDClient\Model\Instances\InstanceTypes\FetchInstanceType;
use dhope0000\LXDClient\Model\Instances\InstanceTypes\InsertInstanceType;

class AddInstanceType
{
    private FetchInstanceType $fetchInstanceType;
    private InsertInstanceType $insertInstanceType;

    public function __construct(
        FetchInstanceType $fetchInstanceType,
        InsertInstanceType $insertInstanceType
    ) {
        $this->fetchInstanceType = $fetchInstanceType;
        $this->insertInstanceType = $insertInstanceType;
    }

    public function add(int $userId, int $providerId, string $name, float $cpu, float $mem) :void
    {
        if ($this->fetchInstanceType->fetchByName($name)) {
            throw new \Exception("Already have a instance with this name, they must be globally unique", 1);
        }

        if ($cpu <= 0) {
            throw new \Exception("CPU must be greater than 0", 1);
        }

        if ($mem <= 0) {
            throw new \Exception("Memory must be greater than 0", 1);
        }

        $this->insertInstanceType->insert($providerId, $name, $cpu, $mem);
    }
}

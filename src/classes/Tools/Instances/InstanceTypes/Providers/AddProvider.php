<?php

namespace dhope0000\LXDClient\Tools\Instances\InstanceTypes\Providers;

use dhope0000\LXDClient\Model\Instances\InstanceTypes\Providers\InsertProvider;

class AddProvider
{
    private InsertProvider $insertProvider;

    public function __construct(
        InsertProvider $insertProvider
    ) {
        $this->insertProvider = $insertProvider;
    }

    public function add(int $userId, string $name) :void
    {
        $this->insertProvider->insert($name);
    }
}

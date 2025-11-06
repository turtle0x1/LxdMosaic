<?php

namespace dhope0000\LXDClient\Tools\Instances\InstanceTypes\Providers;

use dhope0000\LXDClient\Model\Instances\InstanceTypes\DeleteTypes;
use dhope0000\LXDClient\Model\Instances\InstanceTypes\Providers\DeleteProvider;

class RemoveProvider
{
    public function __construct(
        private readonly DeleteTypes $deleteTypes,
        private readonly DeleteProvider $deleteProvider
    ) {
    }

    public function remove(int $providerId)
    {
        //NOTE Setting the FK to cascade would be nice, but ive made terrible
        //     design descisions.
        $this->deleteTypes->deleteAllForProvider($providerId);
        $this->deleteProvider->delete($providerId);
    }
}

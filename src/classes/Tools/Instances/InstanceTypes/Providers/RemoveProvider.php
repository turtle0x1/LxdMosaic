<?php

namespace dhope0000\LXDClient\Tools\Instances\InstanceTypes\Providers;

use dhope0000\LXDClient\Model\Instances\InstanceTypes\Providers\DeleteProvider;
use dhope0000\LXDClient\Model\Instances\InstanceTypes\DeleteTypes;

class RemoveProvider
{
    private DeleteTypes $deleteTypes;
    private DeleteProvider $deleteProvider;

    public function __construct(
        DeleteTypes $deleteTypes,
        DeleteProvider $deleteProvider
    ) {
        $this->deleteTypes = $deleteTypes;
        $this->deleteProvider = $deleteProvider;
    }

    public function remove(int $providerId) :void
    {
        //NOTE Setting the FK to cascade would be nice, but ive made terrible
        //     design descisions.
        $this->deleteTypes->deleteAllForProvider($providerId);
        $this->deleteProvider->delete($providerId);
    }
}

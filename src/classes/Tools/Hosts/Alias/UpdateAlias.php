<?php

namespace dhope0000\LXDClient\Tools\Hosts\Alias;

use dhope0000\LXDClient\Model\Hosts\Alias\SetAlias;
use dhope0000\LXDClient\Model\Hosts\Alias\HaveAlias;

class UpdateAlias
{
    public function __construct(
        SetAlias $setAlias,
        HaveAlias $haveAlias
    ) {
        $this->setAlias = $setAlias;
        $this->haveAlias = $haveAlias;
    }

    public function update(int $hostId, string $alias)
    {
        if ($this->haveAlias->have($alias)) {
            throw new \Exception("Already have host under this alias", 1);
        }

        $this->setAlias->set($hostId, $alias);
        return true;
    }
}

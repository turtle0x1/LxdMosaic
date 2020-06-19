<?php

namespace dhope0000\LXDClient\Tools\Hosts\Settings;

use dhope0000\LXDClient\Model\Hosts\Settings\SetHostSettings;
use dhope0000\LXDClient\Model\Hosts\Settings\Alias\HaveAlias;

class UpdateHostSettings
{
    public function __construct(
        SetHostSettings $setHostSettings,
        HaveAlias $haveAlias
    ) {
        $this->setHostSettings = $setHostSettings;
        $this->haveAlias = $haveAlias;
    }

    public function update(int $hostId, string $alias, int $supportsLoadAverages)
    {
        if ($this->haveAlias->have($hostId, $alias)) {
            throw new \Exception("Already have host under this alias", 1);
        }

        $this->setHostSettings->set($hostId, $alias, $supportsLoadAverages);
        return true;
    }
}

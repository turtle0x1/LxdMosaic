<?php

namespace dhope0000\LXDClient\Tools\Hosts\Settings;

use dhope0000\LXDClient\Model\Hosts\Settings\Alias\HaveAlias;
use dhope0000\LXDClient\Model\Hosts\Settings\SetHostSettings;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;

class UpdateHostSettings
{
    public function __construct(
        private readonly SetHostSettings $setHostSettings,
        private readonly HaveAlias $haveAlias,
        private readonly FetchUserDetails $fetchUserDetails
    ) {
    }

    public function update(int $userId, int $hostId, string $alias, int $supportsLoadAverages)
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId);

        if (!$isAdmin) {
            throw new \Exception('Not allowed to update host settings', 1);
        }

        if ($this->haveAlias->have($hostId, $alias)) {
            throw new \Exception('Already have host under this alias', 1);
        }

        $this->setHostSettings->set($hostId, $alias, $supportsLoadAverages);
        return true;
    }
}

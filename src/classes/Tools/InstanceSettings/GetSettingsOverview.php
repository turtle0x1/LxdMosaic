<?php

namespace dhope0000\LXDClient\Tools\InstanceSettings;

use dhope0000\LXDClient\Model\Users\FetchTokens;

class GetSettingsOverview
{
    public function __construct(
        private readonly FetchTokens $fetchTokens
    ) {
    }

    public function get(string $userId): array
    {
        return [
            'permanentTokens' => $this->fetchTokens->fetchPermanentTokenHeaders($userId),
        ];
    }
}

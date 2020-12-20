<?php

namespace dhope0000\LXDClient\Tools\InstanceSettings;

use dhope0000\LXDClient\Model\Users\FetchTokens;

class GetSettingsOverview
{
    public function __construct(FetchTokens $fetchTokens)
    {
        $this->fetchTokens = $fetchTokens;
    }

    public function get(string $userId) :array
    {
        return [
            "permanentTokens"=>$this->fetchTokens->fetchPermanentTokenHeaders($userId)
        ];
    }
}

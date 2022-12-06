<?php

namespace dhope0000\LXDClient\Tools\CloudConfig\Contents;

use dhope0000\LXDClient\Model\CloudConfig\GetConfig;

class GetLatest
{
    private GetConfig $getConfig;

    public function __construct(GetConfig $getConfig)
    {
        $this->getConfig = $getConfig;
    }

    public function getLatest(int $cloudConfigId) :array
    {
        $latest = $this->getConfig->getLatestConfig($cloudConfigId);
        if (empty($latest)) {
            return [
                "revisionId"=>null,
                "cloudConfigId"=>$cloudConfigId,
                "data"=>"",
                "imageDetails"=>[],
                "envVariables"=>[]
            ];
        }

        $latest["imageDetails"] = $this->getJsonOrEmptyArray($latest, "imageDetails");
        $latest["envVariables"] = $this->getJsonOrEmptyArray($latest, "envVariables");

        return $latest;
    }

    private function getJsonOrEmptyArray(array $array, string $key) :array
    {
        return !empty($array[$key]) ? json_decode($array[$key], true) : [];
    }
}

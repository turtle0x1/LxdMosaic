<?php

namespace dhope0000\LXDClient\Tools\Analytics;

use dhope0000\LXDClient\Model\Analytics\FetchLatestData;

class GetLatestData
{
    public function __construct(FetchLatestData $fetchLatestData)
    {
        $this->fetchLatestData = $fetchLatestData;
    }

    public function get()
    {
        $latestData = $this->fetchLatestData->lastHour();

        if (count($latestData) < 2) {
            return ["warning"=>"Not Enough Data, 10 minutes is minimum time"];
        }

        return $this->prepareForGraphs($latestData);
    }

    private function prepareForGraphs($data)
    {
        $output = [
            "memory"=>[
                "data"=>[],
                "labels"=>[]
            ],
            "activeContainers"=>[
                "data"=>[],
                "labels"=>[]
            ],
            "storageUsage"=>[
                "data"=>[],
                "labels"=>[]
            ]
        ];
        foreach ($data as $entry) {
            $output["memory"]["data"][] = $entry["memoryUsage"];
            $output["activeContainers"]["data"][] = $entry["activeContainers"];
            $output["storageUsage"]["data"][] = $entry["storageUsage"];

            $date = (new \DateTime($entry["dateTime"]))->format("H:i");

            $output["memory"]["labels"][] = $date;
            $output["activeContainers"]["labels"][] = $date;
            $output["storageUsage"]["labels"][] = $date;
        }
        return $output;
    }
}

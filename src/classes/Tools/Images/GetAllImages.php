<?php
namespace dhope0000\LXDClient\Tools\Images;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Universe;

class GetAllImages
{
    private Universe $universe;

    public function __construct(Universe $universe)
    {
        $this->universe = $universe;
    }

    public function getAllHostImages(int $userId)
    {
        $clustersAndHosts = $this->universe->getEntitiesUserHasAccesTo($userId, "images");

        foreach ($clustersAndHosts["clusters"] as $clusterIndex => $cluster) {
            foreach ($cluster["members"] as $hostIndex => &$host) {
                $host->setCustomProp("images", $this->getImages($host));
            }
        }

        foreach ($clustersAndHosts["standalone"]["members"] as $host) {
            $host->setCustomProp("images", $this->getImages($host));
        }

        return $clustersAndHosts;
    }

    private function getImages(Host $host)
    {
        if (!$host->hostOnline()) {
            return [];
        }

        //TODO Recursion
        $ids = $host->getCustomProp("images");
        $details = [];

        foreach ($ids as $fingerprint) {
            $details[] = $host->images->info($fingerprint);
        }

        usort($details, function ($a, $b) {
            return $a["properties"]["description"] > $b["properties"]["description"] ? 1 : -1;
        });

        return $details;
    }
}

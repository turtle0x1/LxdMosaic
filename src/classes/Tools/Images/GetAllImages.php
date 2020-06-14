<?php
namespace dhope0000\LXDClient\Tools\Images;

use dhope0000\LXDClient\Tools\Hosts\GetClustersAndStandaloneHosts;
use dhope0000\LXDClient\Objects\Host;

class GetAllImages
{
    public function __construct(GetClustersAndStandaloneHosts $getClustersAndStandaloneHosts)
    {
        $this->getClustersAndStandaloneHosts = $getClustersAndStandaloneHosts;
    }

    public function getAllHostImages()
    {
        $clustersAndHosts = $this->getClustersAndStandaloneHosts->get(true);

        foreach ($clustersAndHosts["clusters"] as $clusterIndex => $cluster) {
            foreach ($cluster["members"] as $hostIndex => &$host) {
                $host->setCustomProp("images", $this->getImages($host));
            }
        }

        foreach ($clustersAndHosts["standalone"]["members"] as $index => &$host) {
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
        $ids = $host->images->all();
        $details = [];

        foreach ($ids as $fingerprint) {
            $details[] = $host->images->info($fingerprint);
        }

        return $details;
    }
}

<?php
namespace dhope0000\LXDClient\Tools\Images;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Tools\Hosts\GetClustersAndStandaloneHosts;

class GetAllImages
{
    public function __construct(LxdClient $lxdClient, GetClustersAndStandaloneHosts $getClustersAndStandaloneHosts)
    {
        $this->client = $lxdClient;
        $this->getClustersAndStandaloneHosts = $getClustersAndStandaloneHosts;
    }

    public function getAllHostImages()
    {
        $clustersAndHosts = $this->getClustersAndStandaloneHosts->get();

        foreach ($clustersAndHosts["clusters"] as $clusterIndex => $cluster) {
            foreach ($cluster["members"] as $hostIndex => $host) {
                $images = $this->getImages($host);
                $clustersAndHosts["clusters"][$clusterIndex]["members"][$hostIndex] = $images;
            }
        }

        foreach ($clustersAndHosts["standalone"]["members"] as $index => $host) {
            $clustersAndHosts["standalone"]["members"][$index] =  $this->getImages($host);
        }

        return $clustersAndHosts;
    }

    private function getImages(array $host)
    {
        //TODO Hacky as anything but its late and I dont want to pull at threads
        if (isset($host["hostOnline"]) && $host["hostOnline"] != true) {
            return [
                "hostAlias"=>$host["alias"],
                "hostId"=>$host["hostId"],
                "online"=>false,
                "images"=>[]
            ];
        }

        $client = $this->client->getANewClient($host["hostId"], false);
        $ids = $client->images->all();
        $details = [];

        foreach ($ids as $fingerprint) {
            $details[] = $client->images->info($fingerprint);
        }

        return [
            "hostAlias"=>$host["alias"],
            "online"=>true,
            "images"=>$details,
            "hostId"=>$host["hostId"]
        ];
    }
}

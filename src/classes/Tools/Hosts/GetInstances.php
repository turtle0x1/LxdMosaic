<?php
namespace dhope0000\LXDClient\Tools\Hosts;

use dhope0000\LXDClient\Tools\Hosts\GetClustersAndStandaloneHosts;
use dhope0000\LXDClient\Model\Client\LxdClient;

class GetInstances
{
    public function __construct(
        GetClustersAndStandaloneHosts $getClustersAndStandaloneHosts,
        LxdClient $lxdClient
    ) {
        $this->getClustersAndStandaloneHosts = $getClustersAndStandaloneHosts;
        $this->lxdClient = $lxdClient;
    }

    public function get()
    {
        $hosts = $this->getClustersAndStandaloneHosts->get();

        $output = [
            "clusters"=>[],
            "standalone"=>[]
        ];

        foreach ($hosts["clusters"] as $clusterIndex => $cluster) {
            foreach ($cluster["members"] as $memberIndex => $member) {
                if (!isset($output["clusters"][$clusterIndex])) {
                    $output["clusters"][$clusterIndex] = [];
                }

                $output["clusters"][$clusterIndex][] = $this->makeHost($member);
            }
        }

        foreach ($hosts["standalone"]["members"] as $standaloneIndex => $member) {
            if (!isset($output["standalone"][$standaloneIndex])) {
                $output["standalone"][$standaloneIndex] = [];
            }
            $output["standalone"][$standaloneIndex] = $this->makeHost($member);
        }

        return $output;
    }

    private function makeHost(array $member)
    {
        $client = $this->lxdClient->getANewClient($member["hostId"]);

        return [
            "hostId"=>$member["hostId"],
            "alias"=>$member["alias"],
            "instances"=>$client->instances->all()
        ];
    }
}

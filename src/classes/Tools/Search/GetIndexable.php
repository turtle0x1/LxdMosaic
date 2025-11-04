<?php

namespace dhope0000\LXDClient\Tools\Search;

use dhope0000\LXDClient\Model\Hosts\HostList;
use dhope0000\LXDClient\Tools\Hosts\HasExtension;

class GetIndexable
{
    private $hostList;
    private $hasExtension;

    public function __construct(
        HostList $hostList,
        HasExtension $hasExtension
    ) {
        $this->hostList = $hostList;
        $this->hasExtension = $hasExtension;
    }

    public function get(): array
    {
        $hosts = $this->hostList->getOnlineHostsWithDetails();

        $output = [];

        $recursionLevel = 2;

        foreach ($hosts as $host) {
            $supportsProjects = $this->hasExtension->checkWithHost($host, "projects");
            $output[$host->getHostId()] = [];
            $allProjects = [["name" => "default", "config" => []]];

            if ($supportsProjects) {
                $allProjects = $host->projects->all(2);
            }

            foreach ($allProjects as $project) {
                $projectName = $project["name"];

                $output[$host->getHostId()][$projectName] = [
                    "instances" => [],
                    "networks" => [],
                    "storage" => [],
                    "images" => [],
                    "profiles" => []
                ];

                if ($supportsProjects) {
                    $host->setProject($projectName);
                }

                $hostId = $host->getHostId();

                $output[$hostId][$projectName]["instances"] = $this->labelArrays($host->instances->all($recursionLevel), "name");

                $isDefaultProfile = $projectName == "default";

                if ($isDefaultProfile || $this->projectHasFeature($project, "features.networks")) {
                    $output[$hostId][$projectName]["networks"] = $this->labelArrays($host->networks->all($recursionLevel), "name");
                }

                if ($isDefaultProfile || $this->projectHasFeature($project, "features.storage")) {
                    $output[$hostId][$projectName]["storage"] = $this->labelArrays($host->storage->all($recursionLevel), "name");
                }

                if ($isDefaultProfile || $this->projectHasFeature($project, "features.images")) {
                    $output[$hostId][$projectName]["images"] = $this->labelArrays($host->images->all($recursionLevel), "fingerprint");
                }

                if ($isDefaultProfile || $this->projectHasFeature($project, "features.profiles")) {
                    $output[$hostId][$projectName]["profiles"] = $this->labelArrays($host->profiles->all($recursionLevel), "name");
                }
            }
        }
        return $output;
    }

    private function projectHasFeature($project, $key)
    {
        return isset($project["config"][$key]) && $project["config"][$key] === "true";
    }

    private function labelArrays(array $items, string $key)
    {
        $out = [];
        foreach ($items as $item) {
            $out[$item[$key]] = $item;
        }
        return $out;
    }
}

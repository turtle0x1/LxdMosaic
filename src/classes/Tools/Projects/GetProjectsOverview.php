<?php

namespace dhope0000\LXDClient\Tools\Projects;

use dhope0000\LXDClient\Tools\Universe;
use dhope0000\LXDClient\Tools\Hosts\HasExtension;

class GetProjectsOverview
{
    public function __construct(Universe $universe, HasExtension $hasExtension)
    {
        $this->universe = $universe;
        $this->hasExtension = $hasExtension;
    }

    //Based on https://github.com/lxc/lxd/issues/7946#issuecomment-703367651
    public function get($userId)
    {
        $clustersAndStandalone = $this->universe->getEntitiesUserHasAccesTo($userId, "projects");

        foreach ($clustersAndStandalone["clusters"] as $cluserIndex => $cluster) {
            $doneCluster = false;
            foreach ($cluster["members"] as $index => $member) {
                if (!$doneCluster) {
                    $this->calculateUsage($member);
                    $doneCluster = true;
                } else {
                    unset($clustersAndStandalone["clusters"][$cluserIndex]["members"][$index]);
                }
            }
        }

        foreach ($clustersAndStandalone["standalone"]["members"] as $member) {
            $this->calculateUsage($member);
        }
        return $clustersAndStandalone;
    }

    private function calculateUsage(&$member)
    {
        if (!$member->hostOnline()) {
            return;
        }

        $hostProjects = [];

        foreach ($member->getCustomProp("projects") as $project) {
            $projectConfig = [];

            if ($this->hasExtension->checkWithHost($member, "projects")) {
                $projectConfig = $member->projects->info($project, 2)["config"];
            }

            $limits = $this->getLimitValues($projectConfig);

            $member->setProject($project);

            $instances = $member->instances->all(2);

            foreach ($instances as $instance) {
                if (isset($instance["type"]) && $instance["type"] == "virtual-machine") {
                    $limits["limits.virtual-machine"]["value"]++;
                } else {
                    $limits["limits.containers"]["value"]++;
                }

                $limits["limits.instances"]["value"]++;
                
                if (isset($instance["state"])) {
                    $limits["limits.memory"]["value"] += $instance["state"]["memory"]["usage"];
                    $limits["limits.processes"]["value"] += $instance["state"]["processes"];
                    $limits["limits.cpu"]["value"] += $instance["state"]["cpu"]["usage"];

                    //TODO https://github.com/lxc/lxd/issues/8173
                    if ($instance["state"]["disk"] != null) {
                        $limits["limits.disk"]["value"] += $instance["state"]["disk"]["root"]["usage"];
                    }
                }
            }
            $limits["limits.networks"]["value"] = count($member->networks->all());
            $images = $member->images->all(2);
            $limits["limits.disk"]["value"] += array_sum(array_column($images, "size"));
            $hostProjects[$project] = $limits;
        }
        $member->setCustomProp("projects", $hostProjects);
    }

    private function getLimitValues($config)
    {
        $expectedKeys = [
            "limits.containers"=>["limit"=>null, "value"=>0],
            "limits.cpu"=>["limit"=>null, "value"=>0],
            "limits.disk"=>["limit"=>null, "value"=>0],
            "limits.memory"=>["limit"=>null, "value"=>0],
            "limits.networks"=>["limit"=>null, "value"=>0],
            "limits.processes"=>["limit"=>null, "value"=>0],
            "limits.virtual-machine"=>["limit"=>null, "value"=>0],
            "limits.instances"=>["limit"=>null, "value"=>0],
        ];

        foreach ($expectedKeys as $key=>$details) {
            $expectedKeys[$key]["limit"] = isset($config[$key]) ? $config[$key] : null;
        }
        return $expectedKeys;
    }
}

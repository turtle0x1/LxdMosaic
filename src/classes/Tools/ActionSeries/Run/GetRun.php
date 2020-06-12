<?php

namespace dhope0000\LXDClient\Tools\ActionSeries\Run;

use dhope0000\LXDClient\Model\ActionSeries\Run\FetchRuns;
use dhope0000\LXDClient\Tools\ActionSeries\Commands\GetCommandTree;
use dhope0000\LXDClient\Model\ActionSeries\Run\Result\FetchCommandResults;

class GetRun
{
    public function __construct(
        FetchRuns $fetchRuns,
        GetCommandTree $getCommandTree,
        FetchCommandResults $fetchCommandResults
    ) {
        $this->fetchRuns = $fetchRuns;
        $this->getCommandTree = $getCommandTree;
        $this->fetchCommandResults = $fetchCommandResults;
    }

    public function get(int $runId)
    {
        $details = $this->fetchRuns->fetchRunDetails($runId);

        $commandTree = $this->getCommandTree->get($details["actionSeries"]);

        $runResults = $this->fetchCommandResults->fetchByRunGroupedByHost($runId);

        $formated = [];

        foreach ($runResults as $host => $details) {
            $formated[$host] = [
                "instances"=>[]
            ];
            foreach ($details as $result) {
                if (!isset($formated[$host]["instances"][$result["instance"]])) {
                    $formated[$host]["instances"][$result["instance"]] = [];
                }
                $formated[$host]["instances"][$result["instance"]][$result["commandId"]] = $result;
            }
        }

        $runResults = $this->buildCommandTree($formated, $commandTree);

        return [
            "details"=>$details,
            "runResults"=>$runResults
        ];
    }

    private function buildCommandTree(array $formated, array $commandTree)
    {
        $firstCommand = $commandTree[0];
        // Yikes we gon regret this on lots of really big trees
        foreach ($formated as $hostId => $instances) {
            $hostTrees = [];

            foreach ($instances["instances"] as $instance => $results) {
                $result = $instances["instances"][$instance][$firstCommand["id"]];
                $firstCommand["result"] = $result;

                $firstCommand["children"] = $this->addResult($firstCommand["children"], $result["return"], $formated, $hostId, $instance);
                $hostTrees[$instance] = $firstCommand;
            }
            $formated[$hostId]["instances"] = $hostTrees;
        }
        return $formated;
    }

    private function addResult(&$children, $resultToFollow, $formated, $hostId, $instance)
    {
        foreach ($children as $childIndex => $child) {
            if ($child["parentReturnAction"] == $resultToFollow) {
                $result = $formated[$hostId]["instances"][$instance][$child["id"]];
                $children[$childIndex]["result"] = $result;

                if (empty($child["children"])) {
                    return $children;
                }

                $children[$childIndex]["children"] = $this->addResult($child["children"], $result["return"], $formated, $hostId, $instance);
            }
        }
        return $children;
    }
}

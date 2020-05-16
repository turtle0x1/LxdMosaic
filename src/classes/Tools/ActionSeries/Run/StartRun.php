<?php

namespace dhope0000\LXDClient\Tools\ActionSeries\Run;

use dhope0000\LXDClient\Model\ActionSeries\Run\InsertRun;
use dhope0000\LXDClient\Tools\ActionSeries\Commands\GetCommandTree;
use dhope0000\LXDClient\Tools\ActionSeries\Run\ExecuteCommand;
use dhope0000\LXDClient\Model\ActionSeries\Run\UpdateRun;

class StartRun
{
    private $runId = null;

    public function __construct(
        InsertRun $insertRun,
        GetCommandTree $getCommandTree,
        ExecuteCommand $executeCommand,
        UpdateRun $updateRun
    ) {
        $this->insertRun = $insertRun;
        $this->getCommandTree = $getCommandTree;
        $this->executeCommand = $executeCommand;
        $this->updateRun = $updateRun;
    }

    public function start(int $userId, int $seriesId, array $instancesByHost)
    {
        $this->insertRun->insert($userId, $seriesId);

        $this->runId = $this->insertRun->getId();

        $commandTree = $this->getCommandTree->get($seriesId);
        try {
            foreach ($instancesByHost as $hostId => $instances) {
                foreach ($instances as $instance) {
                    $this->traverseTree($hostId, $instance, $commandTree);
                }
            }
        } catch (\Exception $e) {
            // Some error logging
            // $this->updateRun->setFailed($runId);
        } finally {
            $this->updateRun->closeRun($this->runId);
        }
    }

    private function traverseTree(int $hostId, string $instance, array $tree)
    {
        foreach ($tree as $command) {
            $result = $this->executeCommand->execute($hostId, $instance, $this->runId, $command["id"]);

            // When we have reached the end of the tree
            if (!isset($command["children"])) {
                break;
            }

            $childBranch = $this->findResultCommandSeries($result, $command["children"]);

            if (empty($childBranch)) {
                throw new \Exception("Reached an un-expected result for {$command["command"]}", 1);
            }

            $this->traverseTree($hostId, $instance, $childBranch);
        }

        return true;
    }

    private function findResultCommandSeries(int $result, array $commandOptions)
    {
        foreach ($commandOptions as $branch) {
            if ((int) $branch["parentReturnAction"] === $result) {
                return [$branch];
            }
        }
    }
}

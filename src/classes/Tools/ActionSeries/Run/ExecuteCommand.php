<?php

namespace dhope0000\LXDClient\Tools\ActionSeries\Run;

use dhope0000\LXDClient\Model\ActionSeries\Commands\FetchCommandDetails;
use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Model\ActionSeries\Run\Result\InsertCommandResult;

class ExecuteCommand
{
    public function __construct(
        FetchCommandDetails $fetchCommandDetails,
        LxdClient $lxdClient,
        InsertCommandResult $insertCommandResult
    ) {
        $this->fetchCommandDetails = $fetchCommandDetails;
        $this->lxdClient = $lxdClient;
        $this->insertCommandResult = $insertCommandResult;
    }

    public function execute(int $hostId, string $instance, $runId, $commandId) :int
    {
        $commandDetails = $this->fetchCommandDetails->fetchDetails($commandId);

        $client = $this->lxdClient->getANewClient($hostId);

        $execTime = new \DateTime();
        $result = $client->instances->execute($instance, $commandDetails["command"], true, [], true);

        $logFiles = $this->getlabeledLogPaths($result["output"]);

        $this->insertCommandResult->insert(
            $runId,
            $commandId,
            $execTime->format("Y-m-d H:i:s"),
            $hostId,
            $instance,
            $logFiles["stdout"],
            $logFiles["stderr"],
            $result["return"]
        );

        return $result["return"];
    }

    private function getlabeledLogPaths($output)
    {
        if (strpos($output[0], 'stdout') !== false) {
            return ["stdout"=>$output[0], "stderr"=>$output[1]];
        // Just incase LXD reverse the order, I dont know why they didn't
        // label the output instead opting for numeric keys :shrug:
        } elseif (strpos($output[1], 'stdout') !== false) {
            return ["stdout"=>$output[1], "stderr"=>$output[0]];
        }
    }
}

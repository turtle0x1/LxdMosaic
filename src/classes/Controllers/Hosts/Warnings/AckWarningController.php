<?php
namespace dhope0000\LXDClient\Controllers\Hosts\Warnings;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Hosts\Warnings\AckWarning;

class AckWarningController
{
    private $ackWarning;

    public function __construct(AckWarning $ackWarning)
    {
        $this->ackWarning = $ackWarning;
    }

    public function ack(int $userId, Host $host, string $id)
    {
        $this->ackWarning->ack($userId, $host, $id);
        return ["state"=>"success", "message"=>"Acknowledged warning", "id"=>$id];
    }
}

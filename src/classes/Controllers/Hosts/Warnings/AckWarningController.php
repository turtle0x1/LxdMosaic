<?php
namespace dhope0000\LXDClient\Controllers\Hosts\Warnings;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Hosts\Warnings\AckWarning;
use Symfony\Component\Routing\Annotation\Route;

class AckWarningController
{
    private $ackWarning;

    public function __construct(AckWarning $ackWarning)
    {
        $this->ackWarning = $ackWarning;
    }

    /**
     * @Route("/api/Hosts/Warnings/AckWarningController/ack", name="api_hosts_warnings_ackwarningcontroller_ack", methods={"POST"})
     */
    public function ack(int $userId, Host $host, string $id)
    {
        $this->ackWarning->ack($userId, $host, $id);
        return ["state"=>"success", "message"=>"Acknowledged warning", "id"=>$id];
    }
}

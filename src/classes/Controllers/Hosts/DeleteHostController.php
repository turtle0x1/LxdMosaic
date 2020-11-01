<?php
namespace dhope0000\LXDClient\Controllers\Hosts;

use dhope0000\LXDClient\Tools\Hosts\RemoveHost;
use Symfony\Component\Routing\Annotation\Route;

class DeleteHostController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(RemoveHost $removeHost)
    {
        $this->removeHost = $removeHost;
    }
    /**
     * @Route("", name="Delete Host")
     */
    public function delete(int $userId, int $hostId)
    {
        $this->removeHost->remove($userId, $hostId);
        return ["state"=>"success", "message"=>"Deleted host"];
    }
}

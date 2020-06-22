<?php
namespace dhope0000\LXDClient\Controllers\Hosts;

use dhope0000\LXDClient\Model\Hosts\DeleteHost;
use Symfony\Component\Routing\Annotation\Route;

class DeleteHostController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(DeleteHost $deleteHost)
    {
        $this->deleteHost = $deleteHost;
    }
    /**
     * @Route("", name="Delete Host")
     */
    public function delete(int $hostId)
    {
        $this->deleteHost->delete($hostId);
        return ["state"=>"success", "message"=>"Deleted host"];
    }
}

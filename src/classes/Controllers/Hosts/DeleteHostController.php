<?php
namespace dhope0000\LXDClient\Controllers\Hosts;

use dhope0000\LXDClient\Tools\Hosts\RemoveHost;
use Symfony\Component\Routing\Annotation\Route;
use \DI\Container;

class DeleteHostController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private RemoveHost $removeHost;
    private Container $container;

    public function __construct(RemoveHost $removeHost, Container $container)
    {
        $this->removeHost = $removeHost;
        $this->container = $container;
    }
    /**
     * @Route("", name="Delete Host")
     */
    public function delete(int $userId, int $hostId)
    {
        $this->container->call(["dhope0000\LXDClient\Model\Database\Database", "beginTransaction"]);
        $this->removeHost->remove($userId, $hostId);
        $this->container->call(["dhope0000\LXDClient\Model\Database\Database", "commitTransaction"]);
        return ["state"=>"success", "message"=>"Deleted host"];
    }
}

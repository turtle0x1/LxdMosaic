<?php
namespace dhope0000\LXDClient\Tools\Containers;

use dhope0000\LXDClient\Model\Client\LxdClient;

class DeleteContainers
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function delete(int $hostId, array $containers)
    {
        $lxd = $this->lxdClient->getANewClient($hostId);

        foreach($containers as $container){
            $state = $lxd->containers->state($container);

            if($state["status_code"] == 103){
                $lxd->containers->setState($container, "stop");
            }

            $lxd->containers->remove($container, true);
        }

        return true;
    }
}

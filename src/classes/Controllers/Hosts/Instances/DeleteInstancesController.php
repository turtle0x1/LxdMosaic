<?php
namespace dhope0000\LXDClient\Controllers\Hosts\Instances;

use dhope0000\LXDClient\Tools\Instances\DeleteInstances;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class DeleteInstancesController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private DeleteInstances $deleteInstances;

    public function __construct(DeleteInstances $deleteInstances)
    {
        $this->deleteInstances = $deleteInstances;
    }

    /**
     * @Route("", name="Delete Instances")
     */
    public function delete(int $userId, Host $host, array $containers)
    {
        $this->deleteInstances->delete($userId, $host, $containers);
        return ["state"=>"success", "message"=>"Delete Containers"];
    }
}

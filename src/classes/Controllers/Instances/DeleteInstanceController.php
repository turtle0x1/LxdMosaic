<?php
namespace dhope0000\LXDClient\Controllers\Instances;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\DeleteInstance;
use Symfony\Component\Routing\Annotation\Route;

class DeleteInstanceController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private DeleteInstance $deleteInstance;

    public function __construct(DeleteInstance $deleteInstance)
    {
        $this->deleteInstance =  $deleteInstance;
    }
    /**
     * @Route("", name="Delete Instance")
     */
    public function delete(
        int $userId,
        Host $host,
        string $container
    ) {
        $this->deleteInstance->delete($userId, $host, $container);
        return ["state"=>"success", "message"=>"Deleting {$host->getAlias()}/$container"];
    }
}

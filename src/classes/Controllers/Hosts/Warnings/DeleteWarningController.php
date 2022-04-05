<?php
namespace dhope0000\LXDClient\Controllers\Hosts\Warnings;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Hosts\Warnings\DeleteWarning;
use Symfony\Component\Routing\Annotation\Route;

class DeleteWarningController
{
    private $deleteWarning;

    public function __construct(DeleteWarning $deleteWarning)
    {
        $this->deleteWarning = $deleteWarning;
    }
    /**
     * @Route("/api/Hosts/Warnings/DeleteWarningController/delete", methods={"POST"}, name="Delete warning", options={"rbac" = "hosts.warnings.delete"})
     */
    public function delete(int $userId, Host $host, string $id)
    {
        $this->deleteWarning->delete($userId, $host, $id);
        return ["state"=>"success", "message"=>"Deleted warning", "id"=>$id];
    }
}

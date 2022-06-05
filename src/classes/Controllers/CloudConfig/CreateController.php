<?php
namespace dhope0000\LXDClient\Controllers\CloudConfig;

use dhope0000\LXDClient\Tools\CloudConfig\Create;
use Symfony\Component\Routing\Annotation\Route;

class CreateController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $create;

    public function __construct(Create $create)
    {
        $this->create = $create;
    }
    /**
     * @Route("", name="Create Cloud Config")
     */
    public function create(string $name, string $namespace, $description = "")
    {
        $this->create->create($name, $namespace, $description);
        return ["state"=>"success", "message"=>"Created cloud config"];
    }
}

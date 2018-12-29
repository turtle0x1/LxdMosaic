<?php
namespace dhope0000\LXDClient\Controllers\CloudConfig;

use dhope0000\LXDClient\Tools\CloudConfig\Create;

class CreateController
{
    public function __construct(Create $create)
    {
        $this->create = $create;
    }

    public function create(string $name, string $namespace, $description = "")
    {
        $this->create->create($name, $namespace, $description);
        return ["state"=>"success", "message"=>"Created cloud config"];
    }
}

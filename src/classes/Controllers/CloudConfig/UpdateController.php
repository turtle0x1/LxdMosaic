<?php
namespace dhope0000\LXDClient\Controllers\CloudConfig;

use dhope0000\LXDClient\Tools\CloudConfig\Update;

class UpdateController
{
    public function __construct(Update $update)
    {
        $this->update = $update;
    }

    public function update(int $cloudConfigId, string $code)
    {
        $this->update->update($cloudConfigId, $code);
        return ["state"=>"success", "message"=>"Save cloud config"];
    }
}

<?php
namespace dhope0000\LXDClient\Controllers\CloudConfig;

use dhope0000\LXDClient\Tools\CloudConfig\Update;
use Symfony\Component\Routing\Annotation\Route;

class UpdateController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private Update $update;

    public function __construct(Update $update)
    {
        $this->update = $update;
    }
    /**
     * @Route("", name="Update Cloud Config")
     */
    public function update(int $cloudConfigId, string $code, array $imageDetails, array $envVariables = []) :array
    {
        $this->update->update($cloudConfigId, $code, $imageDetails, $envVariables);
        return ["state"=>"success", "message"=>"Save cloud config"];
    }
}

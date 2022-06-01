<?php

namespace dhope0000\LXDClient\Tools\CloudConfig;

use dhope0000\LXDClient\Model\CloudConfig\Data\Update as UpdateModel;

class Update
{
    private $updateModel;
    
    public function __construct(UpdateModel $updateModel)
    {
        $this->updateModel = $updateModel;
    }

    public function update(
        int $cloudConfigId,
        string $code,
        array $imageDetails,
        array $envVariables
    ) {
        $imageDetails = json_encode($imageDetails);
        $envVariables = json_encode($envVariables);
        return $this->updateModel->insert($cloudConfigId, $code, $imageDetails, $envVariables);
    }
}

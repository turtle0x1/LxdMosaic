<?php

namespace dhope0000\LXDClient\Controllers\Deployments;

use dhope0000\LXDClient\Model\Deployments\Containers\UpdatePhoneHomeTime;

class UpdatePhoneHomeController
{
    private $updatePhoneHomeTime;
    
    public function __construct(UpdatePhoneHomeTime $updatePhoneHomeTime)
    {
        $this->updatePhoneHomeTime = $updatePhoneHomeTime;
    }

    public function update(int $deploymentId, string $hostname)
    {
        $this->updatePhoneHomeTime->update($deploymentId, $hostname);
        return [
            "state"=>"success",
            "message"=>"Update time"
        ];
    }
}

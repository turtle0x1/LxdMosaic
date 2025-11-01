<?php

namespace dhope0000\LXDClient\Controllers\Deployments;

use dhope0000\LXDClient\Model\Deployments\Containers\UpdatePhoneHomeTime;
use Symfony\Component\Routing\Annotation\Route;

class UpdatePhoneHomeController
{
    private $updatePhoneHomeTime;
    
    public function __construct(UpdatePhoneHomeTime $updatePhoneHomeTime)
    {
        $this->updatePhoneHomeTime = $updatePhoneHomeTime;
    }

    /**
     * @Route("/api/Deployments/UpdatePhoneHomeController/update", name="api_deployments_updatephonehomecontroller_update", methods={"POST"})
     */
    public function update(int $deploymentId, string $hostname)
    {
        $this->updatePhoneHomeTime->update($deploymentId, $hostname);
        return [
            "state"=>"success",
            "message"=>"Update time"
        ];
    }
}

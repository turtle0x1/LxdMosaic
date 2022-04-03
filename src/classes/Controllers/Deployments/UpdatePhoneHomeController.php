<?php

namespace dhope0000\LXDClient\Controllers\Deployments;

use dhope0000\LXDClient\Model\Deployments\Containers\UpdatePhoneHomeTime;
use Symfony\Component\Routing\Annotation\Route;

class UpdatePhoneHomeController
{
    public function __construct(UpdatePhoneHomeTime $updatePhoneHomeTime)
    {
        $this->updatePhoneHomeTime = $updatePhoneHomeTime;
    }
    /**
     * @Route("/api/Deployments/UpdatePhoneHomeController/update", methods={"POST"}, name="Update phonehome status of an instance")
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

<?php

namespace dhope0000\LXDClient\Controllers\Deployments;

use dhope0000\LXDClient\Tools\Deployments\CreateDeployment;
use Symfony\Component\Routing\Annotation\Route;

class CreateController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(CreateDeployment $createDeployment)
    {
        $this->createDeployment = $createDeployment;
    }
    /**
     * @Route("/api/Deployments/CreateController/create", methods={"POST"}, name="Create Deployment")
     */
    public function create(string $name, array $cloudConfigs)
    {
        $deploymentId = $this->createDeployment->create($name, $cloudConfigs);
        return [
            "state"=>"success",
            "message"=>"Created deployment",
            "deploymentId"=>$deploymentId
        ];
    }
}

<?php

namespace dhope0000\LXDClient\Controllers\Deployments;

use dhope0000\LXDClient\Tools\Deployments\CreateDeployment;
use Symfony\Component\Routing\Annotation\Route;

class CreateController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private CreateDeployment $createDeployment;

    public function __construct(CreateDeployment $createDeployment)
    {
        $this->createDeployment = $createDeployment;
    }
    /**
     * @Route("", name="Create Deployment")
     */
    public function create(string $name, array $cloudConfigs) :array
    {
        $deploymentId = $this->createDeployment->create($name, $cloudConfigs);
        return [
            "state"=>"success",
            "message"=>"Created deployment",
            "deploymentId"=>$deploymentId
        ];
    }
}

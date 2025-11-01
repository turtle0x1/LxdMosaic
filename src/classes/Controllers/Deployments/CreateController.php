<?php

namespace dhope0000\LXDClient\Controllers\Deployments;

use dhope0000\LXDClient\Tools\Deployments\CreateDeployment;
use Symfony\Component\Routing\Annotation\Route;

class CreateController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $createDeployment;
    
    public function __construct(CreateDeployment $createDeployment)
    {
        $this->createDeployment = $createDeployment;
    }
    /**
     * @Route("/api/Deployments/CreateController/create", name="Create Deployment", methods={"POST"})
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

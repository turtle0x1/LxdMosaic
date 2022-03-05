<?php

namespace dhope0000\LXDClient\Tools\Deployments\Projects;

use dhope0000\LXDClient\Model\Deployments\Projects\FetchDeploymentProjects;
use dhope0000\LXDClient\Model\Deployments\Projects\InsertDeploymentProject;
use dhope0000\LXDClient\Model\Deployments\Projects\DeleteDeploymentProject;

class SetDeploymentProjects
{
    private $fetchDeploymentProjects;
    private $insertDeploymentProject;
    private $deleteDeploymentProject;

    public function __construct(
        FetchDeploymentProjects $fetchDeploymentProjects,
        InsertDeploymentProject $insertDeploymentProject,
        DeleteDeploymentProject $deleteDeploymentProject
    ) {
        $this->fetchDeploymentProjects = $fetchDeploymentProjects;
        $this->insertDeploymentProject = $insertDeploymentProject;
        $this->deleteDeploymentProject = $deleteDeploymentProject;
    }

    public function set(int $userId, int $deploymentId, array $newProjectsLayout)
    {
        // TODO VALIDATE THE USER HAS ACCESS TO THE DEPLOYMENT
        $currentProjects = $this->fetchDeploymentProjects->fetchAll($deploymentId);
        $currentProjects = $this->groupProjects($currentProjects);

        foreach ($newProjectsLayout as $project) {
            // TODO VALIDATE USER HAS ACCESS TO PROJECT

            if (isset($currentProjects[$project["hostId"]])) {
                if (isset($currentProjects[$project["hostId"]][$project["project"]])) {
                    unset($currentProjects[$project["hostId"]][$project["project"]]);
                    continue;
                }
            }

            $this->insertDeploymentProject->insert(
                $userId,
                $deploymentId,
                $project["hostId"],
                $project["project"]
            );
        }

        // Any projects left over should be removed
        foreach ($currentProjects as $hostId => $projects) {
            foreach ($projects as $project) {
                $this->deleteDeploymentProject->delete($project["id"]);
            }
        }
    }

    private function groupProjects(array $currentProjects)
    {
        $output = [];
        foreach ($currentProjects as $project) {
            if (!isset($output[$project["hostId"]])) {
                $output[$project["hostId"]] = [];
            }
            $output[$project["hostId"]][$project["project"]] = $project;
        }
        return $output;
    }
}

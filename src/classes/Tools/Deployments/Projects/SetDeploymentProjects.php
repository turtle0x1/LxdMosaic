<?php

namespace dhope0000\LXDClient\Tools\Deployments\Projects;

use dhope0000\LXDClient\Tools\Deployments\Authorise\AuthoriseDeploymentAccess;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use dhope0000\LXDClient\Model\Deployments\Projects\FetchDeploymentProjects;
use dhope0000\LXDClient\Model\Deployments\Projects\InsertDeploymentProject;
use dhope0000\LXDClient\Model\Deployments\Projects\DeleteDeploymentProject;

class SetDeploymentProjects
{
    private AuthoriseDeploymentAccess $authoriseDeploymentAccess;
    private ValidatePermissions $validatePermissions;
    private FetchDeploymentProjects $fetchDeploymentProjects;
    private InsertDeploymentProject $insertDeploymentProject;
    private DeleteDeploymentProject $deleteDeploymentProject;

    public function __construct(
        AuthoriseDeploymentAccess $authoriseDeploymentAccess,
        ValidatePermissions $validatePermissions,
        FetchDeploymentProjects $fetchDeploymentProjects,
        InsertDeploymentProject $insertDeploymentProject,
        DeleteDeploymentProject $deleteDeploymentProject
    ) {
        $this->authoriseDeploymentAccess = $authoriseDeploymentAccess;
        $this->validatePermissions = $validatePermissions;
        $this->fetchDeploymentProjects = $fetchDeploymentProjects;
        $this->insertDeploymentProject = $insertDeploymentProject;
        $this->deleteDeploymentProject = $deleteDeploymentProject;
    }

    public function set(int $userId, int $deploymentId, array $newProjectsLayout)
    {
        $this->authoriseDeploymentAccess->authorise($userId, $deploymentId);

        $currentProjects = $this->fetchDeploymentProjects->fetchAll($deploymentId);
        $currentProjects = $this->groupProjects($currentProjects);

        foreach ($newProjectsLayout as $project) {
            // Make sure the user cant add the deployments to projects they
            // can access
            if ($this->validatePermissions->canAccessHostProject(
                $userId,
                $project["hostId"],
                $project["project"]
            ) == false) {
                continue;
            }

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
                // If the user has no access to the project dont remove it
                // as it isn't within their control
                if ($this->validatePermissions->canAccessHostProject(
                    $userId,
                    $project["hostId"],
                    $project["project"]
                ) == false) {
                    continue;
                }
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

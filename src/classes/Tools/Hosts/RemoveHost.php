<?php

namespace dhope0000\LXDClient\Tools\Hosts;

use dhope0000\LXDClient\Model\Hosts\DeleteHost;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;

use dhope0000\LXDClient\Model\Deployments\Containers\DeleteDeploymentInstances;
use dhope0000\LXDClient\Model\Backups\DeleteBackup;
use dhope0000\LXDClient\Model\Hosts\Backups\Instances\Schedules\DeleteBackupSchedules;
use dhope0000\LXDClient\Model\Metrics\DeleteMetrics;
use dhope0000\LXDClient\Model\ProjectAnalytics\DeleteAnalytics;
use dhope0000\LXDClient\Model\Users\AllowedProjects\DeleteUserAccess;
use dhope0000\LXDClient\Model\Users\Projects\DeleteUserProject;

class RemoveHost
{
    private DeleteHost $deleteHost;
    private FetchUserDetails $fetchUserDetails;
    private DeleteDeploymentInstances $deleteDeploymentInstances;
    private DeleteBackup $deleteBackup;
    private DeleteBackupSchedules $deleteBackupSchedules;
    private DeleteMetrics $deleteMetrics;
    private DeleteAnalytics $deleteAnalytics;
    private DeleteUserAccess $deleteUserAccess;
    private DeleteUserProject $deleteUserProject;

    public function __construct(
        DeleteHost $deleteHost,
        FetchUserDetails $fetchUserDetails,
        DeleteDeploymentInstances $deleteDeploymentInstances,
        DeleteBackup $deleteBackup,
        DeleteBackupSchedules $deleteBackupSchedules,
        DeleteMetrics $deleteMetrics,
        DeleteAnalytics $deleteAnalytics,
        DeleteUserAccess $deleteUserAccess,
        DeleteUserProject $deleteUserProject
    ) {
        $this->deleteHost = $deleteHost;
        $this->fetchUserDetails = $fetchUserDetails;
        $this->deleteDeploymentInstances = $deleteDeploymentInstances;
        $this->deleteBackup = $deleteBackup;
        $this->deleteBackupSchedules = $deleteBackupSchedules;
        $this->deleteMetrics = $deleteMetrics;
        $this->deleteAnalytics = $deleteAnalytics;
        $this->deleteUserAccess = $deleteUserAccess;
        $this->deleteUserProject = $deleteUserProject;
    }

    public function remove($userId, int $hostId) :void
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId) === "1";

        if (!$isAdmin) {
            throw new \Exception("Not allowed to delete hosts", 1);
        }

        //NOTE Because all foreign keys prevent deleting (hmm) we have to
        //     manually clean up the database

        $this->deleteBackup->deleteForHost($hostId);
        $this->deleteDeploymentInstances->deleteForHost($hostId);
        $this->deleteBackupSchedules->deleteForHost($hostId);
        $this->deleteMetrics->deleteForHost($hostId);
        $this->deleteAnalytics->deleteForHost($hostId);
        $this->deleteUserAccess->deleteForHost($hostId);
        $this->deleteUserProject->deleteForHost($hostId);

        $this->deleteHost->delete($hostId);
    }
}

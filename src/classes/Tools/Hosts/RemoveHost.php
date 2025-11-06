<?php

namespace dhope0000\LXDClient\Tools\Hosts;

use dhope0000\LXDClient\Model\Backups\DeleteBackup;
use dhope0000\LXDClient\Model\Deployments\Containers\DeleteDeploymentInstances;
use dhope0000\LXDClient\Model\Hosts\Backups\Instances\Schedules\DeleteBackupSchedules;
use dhope0000\LXDClient\Model\Hosts\DeleteHost;
use dhope0000\LXDClient\Model\Metrics\DeleteMetrics;
use dhope0000\LXDClient\Model\ProjectAnalytics\DeleteAnalytics;
use dhope0000\LXDClient\Model\Users\AllowedProjects\DeleteUserAccess;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Model\Users\Projects\DeleteUserProject;

class RemoveHost
{
    public function __construct(
        private readonly DeleteHost $deleteHost,
        private readonly FetchUserDetails $fetchUserDetails,
        private readonly DeleteDeploymentInstances $deleteDeploymentInstances,
        private readonly DeleteBackup $deleteBackup,
        private readonly DeleteBackupSchedules $deleteBackupSchedules,
        private readonly DeleteMetrics $deleteMetrics,
        private readonly DeleteAnalytics $deleteAnalytics,
        private readonly DeleteUserAccess $deleteUserAccess,
        private readonly DeleteUserProject $deleteUserProject
    ) {
    }

    public function remove($userId, int $hostId)
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId);

        if (!$isAdmin) {
            throw new \Exception('Not allowed to delete hosts', 1);
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

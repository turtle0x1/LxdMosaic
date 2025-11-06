<?php

namespace dhope0000\LXDClient\Tools\ProjectAnalytics;

use dhope0000\LXDClient\Model\ProjectAnalytics\InsertAnalytic;
use dhope0000\LXDClient\Model\ProjectAnalytics\Types\FetchProjectAnalyticsTypes;
use dhope0000\LXDClient\Model\Users\FetchUsers;
use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Projects\GetProjectsOverview;

class StoreProjectsOverview
{
    private $knownTypes = [];

    public function __construct(
        private readonly FetchUsers $fetchUsers,
        private readonly FetchProjectAnalyticsTypes $fetchProjectAnalyticsTypes,
        private readonly GetProjectsOverview $getProjectsOverview,
        private readonly InsertAnalytic $insertAnalytic
    ) {
    }

    public function storeCurrent()
    {
        $date = new \DateTime();

        $this->knownTypes = $this->fetchProjectAnalyticsTypes->fetchKnownKeysToIds();

        $userId = $this->fetchUsers->fetchAnyAdminUserId();

        if (!is_numeric($userId)) {
            throw new \Exception("Couldn't find any admin user id, please create one", 1);
        }

        $clustersAndStandalone = $this->getProjectsOverview->get($userId);

        foreach ($clustersAndStandalone['clusters'] as $cluserIndex => $cluster) {
            foreach ($cluster['members'] as $index => $member) {
                $this->storeHostDetails($date, $member);
            }
        }

        foreach ($clustersAndStandalone['standalone']['members'] as $member) {
            $this->storeHostDetails($date, $member);
        }
    }

    private function storeHostDetails(\DateTimeInterface $date, Host $host)
    {
        $allStats = $host->getCustomProp('projects');
        foreach ($allStats as $project => $stats) {
            foreach ($stats as $key => $details) {
                if (!isset($this->knownTypes[$key])) {
                    continue;
                }
                $typeId = $this->knownTypes[$key];
                $this->insertAnalytic->insert(
                    $date,
                    $host->getHostId(),
                    $project,
                    $typeId,
                    $details['value'],
                    $details['limit']
                );
            }
        }
    }
}

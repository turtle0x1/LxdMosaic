<?php

namespace dhope0000\LXDClient\Tools\Hosts;

use dhope0000\LXDClient\Tools\Instances\GetHostsInstances;
use dhope0000\LXDClient\Tools\Hosts\GetResources;
use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Hosts\HasExtension;
use dhope0000\LXDClient\Constants\LxdApiExtensions;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Model\Users\AllowedProjects\FetchAllowedProjects;
use dhope0000\LXDClient\Tools\ProjectAnalytics\GetGraphableProjectAnalytics;

class GetHostOverview
{
    public function __construct(
        GetHostsInstances $getHostsInstances,
        GetResources $getResources,
        HasExtension $hasExtension,
        FetchUserDetails $fetchUserDetails,
        FetchAllowedProjects $fetchAllowedProjects,
        GetGraphableProjectAnalytics $getGraphableProjectAnalytics
    ) {
        $this->getHostsInstances = $getHostsInstances;
        $this->getResources = $getResources;
        $this->hasExtension = $hasExtension;
        $this->fetchUserDetails = $fetchUserDetails;
        $this->fetchAllowedProjects = $fetchAllowedProjects;
        $this->getGraphableProjectAnalytics = $getGraphableProjectAnalytics;
    }

    public function get(int $userId, Host $host)
    {
        $supportsWarnings = $this->hasExtension->checkWithHost($host, LxdApiExtensions::WARNINGS);

        $isAdmin = $this->fetchUserDetails->isAdmin($userId);

        $warnings = [];

        if ($supportsWarnings && $isAdmin) {
            $warnings = $host->warnings->all();
        }

        $resources = $this->getResources->getHostExtended($host);

        if (!$isAdmin) {
            $resources["projects"] = $this->fetchAllowedProjects->fetchForUserHost($userId, $host->getHostId());
        }

        $projectAnalytics = $this->getGraphableProjectAnalytics->getCurrent($userId, "-30 minutes", $host)["totals"];

        return [
            "header"=>$host,
            "resources"=>$resources,
            "warnings"=>$warnings,
            "projectAnalytics"=>$projectAnalytics
        ];
    }
}

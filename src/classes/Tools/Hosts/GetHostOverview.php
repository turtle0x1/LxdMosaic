<?php

namespace dhope0000\LXDClient\Tools\Hosts;

use dhope0000\LXDClient\Tools\Hosts\GetResources;
use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Hosts\HasExtension;
use dhope0000\LXDClient\Constants\LxdApiExtensions;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Model\Users\AllowedProjects\FetchAllowedProjects;
use dhope0000\LXDClient\Tools\ProjectAnalytics\GetGraphableProjectAnalytics;

class GetHostOverview
{
    private GetResources $getResources;
    private HasExtension $hasExtension;
    private FetchUserDetails $fetchUserDetails;
    private FetchAllowedProjects $fetchAllowedProjects;
    private GetGraphableProjectAnalytics $getGraphableProjectAnalytics;

    public function __construct(
        GetResources $getResources,
        HasExtension $hasExtension,
        FetchUserDetails $fetchUserDetails,
        FetchAllowedProjects $fetchAllowedProjects,
        GetGraphableProjectAnalytics $getGraphableProjectAnalytics
    ) {
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
            $resources["network"]["cards"] = [];
            $resources["network"]["total"] = 0;
            unset($resources["system"]);
            unset($resources["pci"]);
            unset($resources["usb"]);
            unset($resources["storage"]);
            unset($resources["network"]);
            foreach ($resources["cpu"]["sockets"] as &$socket) {
                unset($socket["cache"]);
                $socket["cores"] = count($socket["cores"]);
            }
        } else {
            $only = ["os_name", "os_version", "firewall"];
            $hostDetails = array_intersect_key($host->host->info()["environment"], array_flip((array) $only));
            $resources["hostDetails"] = $hostDetails;
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

<?php
namespace dhope0000\LXDClient\Tools\Hosts;

use dhope0000\LXDClient\Tools\Hosts\HasExtension;
use dhope0000\LXDClient\Objects\Host;

class GetResources
{
    public function __construct(HasExtension $hasExtension)
    {
        $this->hasExtension = $hasExtension;
    }

    public function getHostExtended(Host $host)
    {
        $details = $host->resources->info();

        $supportsProjects = $this->hasExtension->hasWithHostId($host->getHostId(), "projects");
        $resCpuSocket = $this->hasExtension->hasWithHostId($host->getHostId(), "resources_cpu_socket");
        $resGpu = $this->hasExtension->hasWithHostId($host->getHostId(), "resources_gpu");

        $details["extensions"] = [
            "supportsProjects"=>$supportsProjects,
            "resCpuSocket"=>$resCpuSocket,
            "resGpu"=>$resGpu
        ];

        $details["projects"] = [];

        if ($supportsProjects) {
            $details["projects"] = $host->projects->all();
        }

        return $details;
    }
}

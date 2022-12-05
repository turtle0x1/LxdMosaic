<?php

namespace dhope0000\LXDClient\Tools\Storage;

use dhope0000\LXDClient\Tools\Utilities\StringTools;
use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Utilities\UsedByFilter;

class GetStoragePool
{
    private UsedByFilter $usedByFilter;

    public function __construct(UsedByFilter $usedByFilter)
    {
        $this->usedByFilter = $usedByFilter;
    }

    public function get(int $userId, Host $host, string $poolName) :array
    {
        $info = $host->storage->info($poolName);


        if ($info["used_by"] !== null) {
            $info["used_by"] = $this->usedByFilter->filterUserProjects($userId, $host, $info["used_by"]);
        } else {
            $info["used_by"] = [];
        }

        $info["resources"] = $host->storage->resources->info($poolName);

        $volumes = [];

        foreach ($info["used_by"] as $i => $item) {
            if (strpos($item, '/1.0/storage-pools/') !==  false) {
                $url = parse_url($item);

                if ($url == false) {
                    throw new \Exception("Couldn't parse '$item'", 1);
                }

                $project = "default";
                if (isset($url["query"])) {
                    parse_str($url["query"], $query);
                    if (isset($query["project"])) {
                        $project = $query["project"];
                    }
                }

                $parts = explode("/", $url["path"]);
                $name = $parts[count($parts) - 1];

                $pool = StringTools::getStringBetween($url["path"], "/1.0/storage-pools/", '/volumes/');
                $path = str_replace("volumes/", "", substr($url["path"], strpos($url["path"], "/volumes/") + 1));

                $volumes[] = [
                    "path"=>$path,
                    "project"=>$project,
                    "name"=>$name
                ];

                unset($info["used_by"][$i]);
            }
        }

        $info["volumes"] = $volumes;

        $info["used_by"] = StringTools::usedByStringsToLinks(
            $host,
            $info["used_by"]
        );

        return $info;
    }
}

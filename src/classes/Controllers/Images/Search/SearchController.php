<?php

namespace dhope0000\LXDClient\Controllers\Images\Search;

use dhope0000\LXDClient\Tools\Images\GetAllImages;

class SearchController
{
    public function __construct(GetAllImages $getAllImages)
    {
        $this->getAllImages = $getAllImages;
    }

    public function getAllAvailableImages(string $search, string $type = "")
    {
        $allImages = $this->getAllImages->getAllHostImages();
        $output = [];
        $seenFingerPrints = [];
        $unknownCount = 0;
        foreach ($allImages["standalone"]["members"] as $host) {
            $this->doWork($output, $seenFingerPrints, $host->getCustomProp("images"), $unknownCount, $search, $type);
        }

        foreach ($allImages["clusters"] as $cluster) {
            foreach ($cluster["members"] as $host) {
                $this->doWork($output, $seenFingerPrints, $host->getCustomProp("images"), $unknownCount, $search, $type);
            }
        }
        return $output;
    }

    private function doWork(&$output, &$seenFingerPrints, $images, & $unknownCount, $search, $type)
    {
        foreach ($images as $image) {
            if (in_array($image["fingerprint"], $seenFingerPrints)) {
                continue;
            }


            if (!isset($image["properties"]["description"])) {
                $unknownCount++;
                $image["properties"]["description"] = "Unknown $unknownCount";
            } else {
                if (stripos($image["properties"]["description"], $search) === false) {
                    continue;
                }
            }

            if (!isset($image["update_source"]) || empty($image["update_source"])) {
                $image["update_source"] = [
                    "protocol"=>"lxd",
                    "server"=>$host,
                    "provideMyHostsCert"=>true
                ];
            } else {
                $image["update_source"]["provideMyHostsCert"] = false;
            }

            if (!empty($type) && isset($image["type"]) && $image["type"] !== $type) {
                continue;
            }

            $output[] = [
                "description"=>$image["properties"]["description"],
                "details"=>array_merge(
                    $image["update_source"],
                    ["fingerprint"=>$image["fingerprint"]]
                )
            ];
            $seenFingerPrints[] = $image["fingerprint"];
        }
    }
}

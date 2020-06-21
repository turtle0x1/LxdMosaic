<?php

namespace dhope0000\LXDClient\Controllers\Images\Search;

use dhope0000\LXDClient\Tools\Images\GetAllImages;

class SearchController
{
    public function __construct(GetAllImages $getAllImages)
    {
        $this->getAllImages = $getAllImages;
    }

    public function getAllAvailableImages()
    {
        $allImages = $this->getAllImages->getAllHostImages();
        $output = [];
        $seenFingerPrints = [];
        $unknownCount = 0;
        foreach ($allImages as $host => $details) {
            foreach ($details["images"] as $image) {
                if (in_array($image["fingerprint"], $seenFingerPrints)) {
                    continue;
                }


                if (!isset($image["properties"]["description"])) {
                    $unknownCount++;
                    $image["properties"]["description"] = "Unknown $unknownCount";
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
        return $output;
    }
}

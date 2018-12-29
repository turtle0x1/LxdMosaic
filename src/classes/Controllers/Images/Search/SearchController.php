<?php

namespace dhope0000\LxdClient\Controllers\Images\Search;

use dhope0000\LXDClient\Model\Images\GetAllImages;

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
        foreach ($allImages as $images) {
            foreach ($images as $image) {
                if (in_array($image["fingerprint"], $seenFingerPrints)) {
                    continue;
                }

                if (!isset($image["update_source"]) || empty($image["update_source"])) {
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
        return $output;
    }
}

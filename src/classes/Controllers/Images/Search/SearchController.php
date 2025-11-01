<?php

namespace dhope0000\LXDClient\Controllers\Images\Search;

use dhope0000\LXDClient\Tools\Images\GetAllImages;
use Symfony\Component\Routing\Annotation\Route;

class SearchController
{
    private $getAllImages;
    
    public function __construct(GetAllImages $getAllImages)
    {
        $this->getAllImages = $getAllImages;
    }

    /**
     * @Route("/api/Images/Search/SearchController/getAllAvailableImages", name="api_images_search_searchcontroller_getallavailableimages", methods={"POST"})
     */
    public function getAllAvailableImages(int $userId, string $search, string $type = "")
    {
        $allImages = $this->getAllImages->getAllHostImages($userId);
        $output = [];
        $seenFingerPrints = [];
        $unknownCount = 0;
        foreach ($allImages["standalone"]["members"] as $host) {
            $this->doWork($output, $seenFingerPrints, $host->getCustomProp("images"), $unknownCount, $search, $type, $host);
        }

        foreach ($allImages["clusters"] as $cluster) {
            foreach ($cluster["members"] as $host) {
                $this->doWork($output, $seenFingerPrints, $host->getCustomProp("images"), $unknownCount, $search, $type, $host);
            }
        }
        return $output;
    }

    private function doWork(&$output, &$seenFingerPrints, $images, & $unknownCount, $search, $type, $host)
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
                    "server"=>$host->getUrl(),
                    "provideMyHostsCert"=>true
                ];
            } else {
                $image["update_source"]["provideMyHostsCert"] = false;
            }

            if (!empty($type) && isset($image["type"]) && $image["type"] !== $type) {
                continue;
            }

            $variant = "";

            if (isset($image["properties"]["variant"])) {
                if ($image["properties"]["variant"] == "cloud") {
                    $variant = "<i class='fas fa-cloud'></i>";
                } elseif ($image["properties"]["variant"] == "desktop") {
                    $variant = "<i class='fas fa-desktop'></i>";
                }
            }

            $output[] = [
                "description"=> "<i class='fas fa-server me-2'></i>" . $host->getAlias() . " <i class='fas fa-image ms-2 me-2'></i> " . $image["properties"]["description"] . " " . $variant,
                "origDescription"=>$image["properties"]["description"],
                "details"=>array_merge(
                    $image["update_source"],
                    ["fingerprint"=>$image["fingerprint"]]
                )
            ];
            $seenFingerPrints[] = $image["fingerprint"];
        }
    }
}

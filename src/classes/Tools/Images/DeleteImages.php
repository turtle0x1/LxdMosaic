<?php
namespace dhope0000\LXDClient\Tools\Images;

use dhope0000\LXDClient\Model\Hosts\GetDetails;

class DeleteImages
{
    public function __construct(GetDetails $getDetails)
    {
        $this->getDetails = $getDetails;
    }

    public function delete($imageData)
    {
        $output = [];
        $hostCache = [];
        foreach ($imageData as $image) {
            $this->validateOrThrowImageDetails($image);
            
            if (!isset($hostCache[$image["hostId"]])) {
                $hostCache[$image["hostId"]] = $this->getDetails->fetchHost($image["hostId"]);
            }

            $host = $hostCache[$image["hostId"]];
            $output[] = $host->images->remove($image["fingerprint"]);
        }
        return $output;
    }

    private function validateOrThrowImageDetails(array $image)
    {
        if (!isset($image["hostId"]) && !is_numeric($image["hostId"])) {
            throw new \Exception("Host Details Missing", 1);
        } elseif (!isset($image["fingerprint"]) && !is_string($image["fingerprint"])) {
            throw new \Exception("Fingerprint Missing", 1);
        }
        return true;
    }
}

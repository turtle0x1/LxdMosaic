<?php
namespace dhope0000\LXDClient\Tools\Images;

use dhope0000\LXDClient\Model\Hosts\GetDetails;
use dhope0000\LXDClient\Model\Users\AllowedProjects\FetchAllowedProjects;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;

class DeleteImages
{
    private GetDetails $getDetails;
    private FetchAllowedProjects $fetchAllowedProjects;
    private FetchUserDetails $fetchUserDetails;

    public function __construct(
        GetDetails $getDetails,
        FetchAllowedProjects $fetchAllowedProjects,
        FetchUserDetails $fetchUserDetails
    ) {
        $this->getDetails = $getDetails;
        $this->fetchAllowedProjects = $fetchAllowedProjects;
        $this->fetchUserDetails = $fetchUserDetails;
    }

    public function delete(int $userId, array $imageData) :array
    {
        $output = [];
        $hostCache = [];

        $isAdmin = (bool) $this->fetchUserDetails->isAdmin($userId);
        $allowedHostsProjects = $this->fetchAllowedProjects->fetchAll($userId);

        foreach ($imageData as $image) {
            $this->validateOrThrowImageDetails($image);

            if (!isset($hostCache[$image["hostId"]])) {
                //NOTE we dont need to check the project because they cant
                //     set it, so it will try to use the project they
                //     are currently set to
                if (!$isAdmin && !isset($allowedHostsProjects[$image["hostId"]])) {
                    throw new \Exception("No access to host", 1);
                }

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

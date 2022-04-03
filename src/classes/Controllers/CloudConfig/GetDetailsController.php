<?php
namespace dhope0000\LXDClient\Controllers\CloudConfig;

use dhope0000\LXDClient\Tools\CloudConfig\GetDetails;
use Symfony\Component\Routing\Annotation\Route;

class GetDetailsController
{
    public function __construct(GetDetails $getDetails)
    {
        $this->getDetails = $getDetails;
    }
    /**
     * @Route("/api/CloudConfig/GetDetailsController/get", methods={"POST"}, name="Get cloud config file")
     */
    public function get(int $id)
    {
        return $this->getDetails->get($id);
    }
}

<?php
namespace dhope0000\LXDClient\Controllers\CloudConfig;

use dhope0000\LXDClient\Tools\CloudConfig\GetDetails;
use Symfony\Component\Routing\Annotation\Route;

class GetDetailsController
{
    private $getDetails;

    public function __construct(GetDetails $getDetails)
    {
        $this->getDetails = $getDetails;
    }

    /**
     * @Route("/api/CloudConfig/GetDetailsController/get", name="api_cloudconfig_getdetailscontroller_get", methods={"POST"})
     */
    public function get(int $id)
    {
        return $this->getDetails->get($id);
    }
}

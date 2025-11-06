<?php

namespace dhope0000\LXDClient\Controllers\CloudConfig;

use dhope0000\LXDClient\Tools\CloudConfig\GetDetails;
use Symfony\Component\Routing\Annotation\Route;

class GetDetailsController
{
    public function __construct(
        private readonly GetDetails $getDetails
    ) {
    }

    /**
     * @Route("/api/CloudConfig/GetDetailsController/get", name="api_cloudconfig_getdetailscontroller_get", methods={"POST"})
     */
    public function get(int $id)
    {
        return $this->getDetails->get($id);
    }
}

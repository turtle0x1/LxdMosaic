<?php
namespace dhope0000\LXDClient\Controllers\CloudConfig;

use dhope0000\LXDClient\Tools\CloudConfig\GetDetails;

class GetDetailsController
{
    private GetDetails $getDetails;

    public function __construct(GetDetails $getDetails)
    {
        $this->getDetails = $getDetails;
    }

    public function get(int $id) :array
    {
        return $this->getDetails->get($id);
    }
}

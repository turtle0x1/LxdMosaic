<?php
namespace dhope0000\LXDClient\Controllers\Instances;

use dhope0000\LXDClient\Tools\Instances\GetInstance;

class GetInstanceController
{
    public function __construct(GetInstance $getInstance)
    {
        $this->getInstance = $getInstance;
    }

    public function get(int $hostId, string $container)
    {
        return $this->getInstance->get($hostId, $container);
    }
}

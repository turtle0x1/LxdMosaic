<?php
namespace dhope0000\LXDClient\Controllers\Instances;

use dhope0000\LXDClient\Tools\Instances\GetInstance;
use dhope0000\LXDClient\Objects\Host;

class GetInstanceController
{
    private GetInstance $getInstance;

    public function __construct(GetInstance $getInstance)
    {
        $this->getInstance = $getInstance;
    }

    public function get(Host $host, string $container)
    {
        return $this->getInstance->get($host, $container);
    }
}

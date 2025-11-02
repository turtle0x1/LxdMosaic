<?php

 namespace dhope0000\LXDClient\Controllers\Instances\Packages;

use dhope0000\LXDClient\Tools\Instances\Packages\GetLatestPackages;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class GetLatestPackagesController
{
    private $getLatestPackages;
    
    public function __construct(GetLatestPackages $getLatestPackages)
    {
        $this->getLatestPackages = $getLatestPackages;
    }
    /**
     * @Route("/api/instances/packages/latest", name="Get instance latest packages", methods={"POST", "GET"})
     */
    public function get(Host $host, string $container)
    {
        return $this->getLatestPackages->get($host, $container);
    }
}

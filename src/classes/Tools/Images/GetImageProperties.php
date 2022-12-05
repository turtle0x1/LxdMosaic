<?php
namespace dhope0000\LXDClient\Tools\Images;

use dhope0000\LXDClient\Objects\Host;

class GetImageProperties
{
    private array $supportedProprties = [
        "public"=>"",
        "auto_update"=>""
    ];

    public function getAll(Host $host, string $fingerprint)
    {
        return $host->images->info($fingerprint);
    }

    public function getFiltertedList(Host $host, string  $fingerprint) :array
    {
        $info = $this->getAll($host, $fingerprint);
        return array_intersect_key($info, $this->supportedProprties);
    }
}

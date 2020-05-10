<?php
namespace dhope0000\LXDClient\Tools\Images;

use dhope0000\LXDClient\Model\Client\LxdClient;

class GetImageProperties
{
    private $supportedProprties = [
        "public"=>"",
        "auto_update"=>""
    ];

    public function __construct(LxdClient $lxdClient)
    {
        $this->client = $lxdClient;
    }

    public function getAll(int $hostId, string $fingerprint)
    {
        $client = $this->client->getANewClient($hostId);
        return $client->images->info($fingerprint);
    }

    public function getFiltertedList(int $hostId, string  $fingerprint)
    {
        $info = $this->getAll($hostId, $fingerprint);
        return array_intersect_key($info, $this->supportedProprties);
    }
}

<?php
namespace dhope0000\LXDClient\Tools\Instances;

use dhope0000\LXDClient\Model\Client\LxdClient;

class CreateImage
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function create(
        int $hostId,
        string $instance,
        string $alias,
        bool $public,
        string $os = null,
        string $description = null
    ) :bool {
        $client = $this->lxdClient->getANewClient($hostId);
        $x = $client->images->createFromContainer($instance, [
            "properties"=>[
                "os" => $os
            ],
            "aliases"=>[
                [
                    "name"=>$alias,
                    "description"=>$description
                ]
            ]
        ]);
        if ($x["err"] !== "") {
            throw new \Exception($x["err"], 1);
        }
        return true;
    }
}

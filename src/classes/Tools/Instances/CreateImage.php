<?php

namespace dhope0000\LXDClient\Tools\Instances;

use dhope0000\LXDClient\Objects\Host;

class CreateImage
{
    public function create(
        Host $host,
        string $instance,
        string $alias,
        bool $public,
        ?string $os = null,
        ?string $description = null
    ): bool {
        $x = $host->images->createFromContainer($instance, [
            'properties' => [
                'os' => $os,
            ],
            'aliases' => [
                [
                    'name' => $alias,
                    'description' => $description,
                ],
            ],
        ]);
        if ($x['err'] !== '') {
            throw new \Exception($x['err'], 1);
        }
        return true;
    }
}

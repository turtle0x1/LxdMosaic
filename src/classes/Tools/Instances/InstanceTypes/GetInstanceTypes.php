<?php

namespace dhope0000\LXDClient\Tools\Instances\InstanceTypes;

use dhope0000\LXDClient\Model\Instances\InstanceTypes\FetchInstanceTypes;

class GetInstanceTypes
{
    public function __construct(FetchInstanceTypes $fetchInstanceTypes)
    {
        $this->fetchInstanceTypes = $fetchInstanceTypes;
    }

    public function getGroupedByProvider()
    {
        $types = $this->fetchInstanceTypes->fetchAll();
        $output = [];
        foreach ($types as $type) {
            if (!isset($output[$type["providerName"]])) {
                $output[$type["providerName"]] = [];
            }
            $output[$type["providerName"]][] = $type;
        }
        return $output;
    }
}

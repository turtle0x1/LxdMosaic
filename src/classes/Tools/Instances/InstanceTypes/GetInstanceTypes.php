<?php

namespace dhope0000\LXDClient\Tools\Instances\InstanceTypes;

use dhope0000\LXDClient\Model\Instances\InstanceTypes\FetchInstanceTypes;

class GetInstanceTypes
{
    private FetchInstanceTypes $fetchInstanceTypes;

    public function __construct(FetchInstanceTypes $fetchInstanceTypes)
    {
        $this->fetchInstanceTypes = $fetchInstanceTypes;
    }

    public function getGroupedByProvider() :array
    {
        $types = $this->fetchInstanceTypes->fetchAll();
        $output = [];
        foreach ($types as $type) {
            if (!isset($output[$type["providerName"]])) {
                $output[$type["providerName"]] = [
                    "providerId"=>$type["providerId"],
                    "types"=>[]
                ];
            }

            if ($type["id"] == null) {
                continue;
            }

            $output[$type["providerName"]]["types"][] = $type;
        }
        return $output;
    }
}

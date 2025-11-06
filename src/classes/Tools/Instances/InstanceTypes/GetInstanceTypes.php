<?php

namespace dhope0000\LXDClient\Tools\Instances\InstanceTypes;

use dhope0000\LXDClient\Model\Instances\InstanceTypes\FetchInstanceTypes;

class GetInstanceTypes
{
    public function __construct(
        private readonly FetchInstanceTypes $fetchInstanceTypes
    ) {
    }

    public function getGroupedByProvider()
    {
        $types = $this->fetchInstanceTypes->fetchAll();
        $output = [];
        foreach ($types as $type) {
            if (!isset($output[$type['providerName']])) {
                $output[$type['providerName']] = [
                    'providerId' => $type['providerId'],
                    'types' => [],
                ];
            }

            if ($type['id'] == null) {
                continue;
            }

            $output[$type['providerName']]['types'][] = $type;
        }
        return $output;
    }
}

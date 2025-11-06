<?php

namespace dhope0000\LXDClient\Tools\InstanceSettings\RecordActions\Convertors\Instances;

use dhope0000\LXDClient\Constants\RecordedActions\Categories;
use dhope0000\LXDClient\Constants\RecordedActions\Methods;
use dhope0000\LXDClient\Interfaces\RecordedActionConvertor;
use dhope0000\LXDClient\Objects\RecordedAction;

class DeleteInstance implements RecordedActionConvertor
{
    #[\Override]
    public function convert(array $recordedAction): RecordedAction
    {
        $title = 'Deleted instance ' . $recordedAction['params']['container'];

        if (isset($recordedAction['params']['host']) && !empty($recordedAction['params']['host'])) {
            if (isset($recordedAction['params']['host']['alias'])) {
                $title .= ' On host ' . $recordedAction['params']['host']['alias'];
            }
        }

        return new RecordedAction(
            $title,
            new \DateTime($recordedAction['date']),
            Categories::INSTANCE,
            Methods::DELETE
        );
    }
}

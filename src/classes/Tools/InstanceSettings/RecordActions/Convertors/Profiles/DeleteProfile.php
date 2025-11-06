<?php

namespace dhope0000\LXDClient\Tools\InstanceSettings\RecordActions\Convertors\Profiles;

use dhope0000\LXDClient\Constants\RecordedActions\Categories;
use dhope0000\LXDClient\Constants\RecordedActions\Methods;
use dhope0000\LXDClient\Interfaces\RecordedActionConvertor;
use dhope0000\LXDClient\Objects\RecordedAction;

class DeleteProfile implements RecordedActionConvertor
{
    #[\Override]
    public function convert(array $recordedAction): RecordedAction
    {
        $title = 'Deleted profile ' . $recordedAction['params']['profile'];

        if (isset($recordedAction['params']['host']) && !empty($recordedAction['params']['host'])) {
            if (isset($recordedAction['params']['host']['alias'])) {
                $title .= ' On host ' . $recordedAction['params']['host']['alias'];
            }
        }

        return new RecordedAction(
            $title,
            new \DateTime($recordedAction['date']),
            Categories::PROFILES,
            Methods::DELETE
        );
    }
}

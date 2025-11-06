<?php

namespace dhope0000\LXDClient\Tools\InstanceSettings\RecordActions\Convertors\Projects;

use dhope0000\LXDClient\Constants\RecordedActions\Categories;
use dhope0000\LXDClient\Constants\RecordedActions\Methods;
use dhope0000\LXDClient\Interfaces\RecordedActionConvertor;
use dhope0000\LXDClient\Objects\RecordedAction;

class DeleteProject implements RecordedActionConvertor
{
    #[\Override]
    public function convert(array $recordedAction): RecordedAction
    {
        $title = 'Deleted project ' . $recordedAction['params']['project'];

        if (isset($recordedAction['params']['host']) && !empty($recordedAction['params']['host'])) {
            if (isset($recordedAction['params']['host']['alias'])) {
                $title .= ' On host ' . $recordedAction['params']['host']['alias'];
            }
        }

        return new RecordedAction(
            $title,
            new \DateTime($recordedAction['date']),
            Categories::PROJECTS,
            Methods::DELETE
        );
    }
}

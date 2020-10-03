<?php

namespace dhope0000\LXDClient\Tools\InstanceSettings\RecordActions\Convertors\Networks;

use dhope0000\LXDClient\Objects\RecordedAction;
use dhope0000\LXDClient\Interfaces\RecordedActionConvertor;
use dhope0000\LXDClient\Constants\RecordedActions\Categories;
use dhope0000\LXDClient\Constants\RecordedActions\Methods;

class DeleteNetwork implements RecordedActionConvertor
{
    public function convert(array $recordedAction) :RecordedAction
    {
        $title = "Deleted network " .  $recordedAction["params"]["network"];

        if (isset($recordedAction["params"]["host"]) && !empty($recordedAction["params"]["host"])) {
            if (isset($recordedAction["params"]["host"]["alias"])) {
                $title .= " On host " . $recordedAction["params"]["host"]["alias"];
            }
        }

        return new RecordedAction(
            $title,
            new \DateTime($recordedAction["date"]),
            Categories::NETWORKS,
            Methods::DELETE
        );
    }
}

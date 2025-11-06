<?php

namespace dhope0000\LXDClient\Interfaces;

use dhope0000\LXDClient\Objects\RecordedAction;

interface RecordedActionConvertor
{
    public function convert(array $recordedAction): RecordedAction;
}

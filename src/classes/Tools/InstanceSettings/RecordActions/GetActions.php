<?php

namespace dhope0000\LXDClient\Tools\InstanceSettings\RecordActions;

use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use dhope0000\LXDClient\Model\InstanceSettings\RecordActions\FetchRecordedActions;

class GetActions
{
    private $insertActionLog;

    public function __construct(ValidatePermissions $validatePermissions, FetchRecordedActions $fetchRecordedActions)
    {
        $this->validatePermissions = $validatePermissions;
        $this->fetchRecordedActions = $fetchRecordedActions;
    }

    public function get(int $userId, int $ammount)
    {
        $this->validatePermissions->isAdminOrThrow($userId);

        return $this->fetchRecordedActions->fetch($ammount);
    }
}

<?php

namespace dhope0000\LXDClient\Tools\InstanceSettings\RecordActions;

use dhope0000\LXDClient\Tools\User\UserSession;
use dhope0000\LXDClient\Model\InstanceSettings\RecordActions\FetchRecordedActions;

class GetActions
{
    private $insertActionLog;

    public function __construct(UserSession $userSession, FetchRecordedActions $fetchRecordedActions)
    {
        $this->userSession = $userSession;
        $this->fetchRecordedActions = $fetchRecordedActions;
    }

    public function get(int $ammount)
    {
        $this->userSession->isAdminOrThrow();

        return $this->fetchRecordedActions->fetch($ammount);
    }
}

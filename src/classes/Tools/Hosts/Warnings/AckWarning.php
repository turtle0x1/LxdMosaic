<?php

namespace dhope0000\LXDClient\Tools\Hosts\Warnings;

use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use dhope0000\LXDClient\Objects\Host;

class AckWarning
{
    private ValidatePermissions $validatePermissions;

    public function __construct(ValidatePermissions $validatePermissions)
    {
        $this->validatePermissions = $validatePermissions;
    }

    public function ack(int $userId, Host $host, string $id) :void
    {
        $this->validatePermissions->isAdminOrThrow($userId);
        $host->warnings->status->acknowledge($id);
    }
}

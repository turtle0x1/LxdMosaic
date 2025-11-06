<?php

namespace dhope0000\LXDClient\Tools\Hosts\Warnings;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;

class DeleteWarning
{
    public function __construct(
        private readonly ValidatePermissions $validatePermissions
    ) {
    }

    public function delete(int $userId, Host $host, string $id)
    {
        $this->validatePermissions->isAdminOrThrow($userId);
        $host->warnings->remove($id);
    }
}

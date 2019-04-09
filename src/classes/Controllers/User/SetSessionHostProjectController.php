<?php

namespace dhope0000\LXDClient\Controllers\User;

use Symfony\Component\HttpFoundation\Session\Session;

class SetSessionHostProjectController
{
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function set(int $hostId, string $project)
    {
        $this->session->set("host/$hostId/project", $project);
        return ["state"=>"success", "message"=>"Changed project to $project"];
    }
}

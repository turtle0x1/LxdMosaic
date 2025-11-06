<?php

namespace dhope0000\LXDClient\Controllers\Projects;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Projects\RenameProject;
use Symfony\Component\Routing\Attribute\Route;

class RenameProjectController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly RenameProject $renameProject
    ) {
    }

    #[Route(path: '/api/Projects/RenameProjectController/rename', name: 'Rename Project', methods: ['POST'])]
    public function rename(Host $host, string $project, string $newName)
    {
        return $this->renameProject->rename($host, $project, $newName);
    }
}

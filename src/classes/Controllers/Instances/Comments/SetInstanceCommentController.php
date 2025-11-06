<?php

namespace dhope0000\LXDClient\Controllers\Instances\Comments;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\Comments\SetInstanceComment;
use Symfony\Component\Routing\Attribute\Route;

class SetInstanceCommentController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly SetInstanceComment $setInstanceComment
    ) {
    }

    #[Route(path: '/api/Instances/Comments/SetInstanceCommentController/set', name: 'Set instance comment', methods: ['POST'])]
    public function set(Host $host, string $container, string $comment)
    {
        $this->setInstanceComment->set($host, $container, $comment);
        return [
            'state' => 'success',
            'message' => 'Set comment',
        ];
    }
}

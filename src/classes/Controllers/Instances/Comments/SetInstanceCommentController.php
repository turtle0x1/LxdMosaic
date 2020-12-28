<?php
namespace dhope0000\LXDClient\Controllers\Instances\Comments;

use dhope0000\LXDClient\Tools\Instances\Comments\SetInstanceComment;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class SetInstanceCommentController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $setInstanceComment;

    public function __construct(SetInstanceComment $setInstanceComment)
    {
        $this->setInstanceComment = $setInstanceComment;
    }
    /**
     * @Route("", name="Set instance comment")
     */
    public function set(
        Host $host,
        string $container,
        string $comment
    ) {
        $this->setInstanceComment->set($host, $container, $comment);
        return ["state"=>"success", "message"=>"Set comment"];
    }
}

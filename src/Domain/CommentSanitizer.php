<?php
namespace Domain;
use Domain\Entities\Comment;
class CommentSanitizer
{
    protected $comment;

    public function __construct(Comment $comment) 
    {
        $this->comment = $comment;
    }

    public function sanitize()
    {
        return strip_tags($this->comment->getText());
    }
}
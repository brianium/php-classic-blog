<?php
namespace Test\Fixtures\Comment;
use Domain\Entities\Comment;
class CommentNoPost extends Comment
{
    public function __construct()
    {
        $this->id = 1;
        $this->text = 'Great post!';
        $this->date = \DateTime::createFromFormat('m/d/Y', '06/20/1986');
        $this->commenter_name = 'Brian Scaturro';
        $this->commenter_email = 'scaturrob@gmail.com';
        $this->commenter_url = 'http://brianscaturro.com';
    }
}
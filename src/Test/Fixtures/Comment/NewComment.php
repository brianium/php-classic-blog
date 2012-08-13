<?php
namespace Test\Fixtures\Comment;
use Domain\Entities\Comment;
class NewComment extends Comment
{
    use CommentFixture;
    public function __construct()
    {
        parent::__construct();
        $this->text = 'Fixtures truly are gnarly!';
        $this->commenter_name = 'Brian Scaturro';
        $this->commenter_email = 'scaturrob@gmail.com';
        $this->commenter_url = 'http://brianscaturro.com';
    }
}
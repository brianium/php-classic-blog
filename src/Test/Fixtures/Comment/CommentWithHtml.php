<?php
namespace Test\Fixtures\Comment;
use Domain\Entities\Comment;
class CommentWithHtml extends Comment
{
    use CommentFixture;
    public function __construct()
    {
        parent::__construct();
        $this->id = 2;
        $this->text = '<h1>Greetings</h1> there blogger! Click <a href="#">here</a> for more info!!! It is going to be <strong>great!</strong>';
        $this->date = \DateTime::createFromFormat('m/d/Y', '06/20/1987');
        $this->commenter_name = 'Comment Guy';
        $this->commenter_email = 'comment@guy.com';
        $this->commenter_url = 'http://commentguy.com';
    }
}
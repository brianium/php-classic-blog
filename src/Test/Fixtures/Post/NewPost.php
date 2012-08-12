<?php
namespace Test\Fixtures\Post;
use Domain\Entities\Post;
class NewPost extends Post
{
    use PostFixture;
    public function __construct()
    {
        parent::__construct();
        $this->title = 'How To Use Test Fixtures';
        $this->excerpt = 'Test fixtures are really helpful.';
        $this->content = 'Just use fixtures. You will not regret it';
    }
}
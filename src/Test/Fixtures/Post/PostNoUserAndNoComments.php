<?php
namespace Test\Fixtures\Post;
use Domain\Entities\Post;
class PostNoUserAndNoComments extends Post
{
    public function __construct()
    {
        parent::__construct();
        $this->id = 1;
        $this->title = 'Unit Testing Is Gnarly';
        $this->excerpt = 'A short summary.';
        $this->content = 'This is post content';
        $this->date = \DateTime::createFromFormat('m/d/Y', '06/20/1986');
    }
}
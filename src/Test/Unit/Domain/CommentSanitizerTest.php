<?php
namespace Test\Unit\Domain;
use Domain\CommentSanitizer;
use Test\TestBase;
class CommentSanitizerTest extends TestBase
{
    protected $comment;

    public function setUp()
    {
        parent::setUp();
        $this->comment = $this->loadFixture('Test\\Fixtures\\Comment\\CommentWithHtml', 'Domain\\Entities\\Comment');
        $this->sanitizer = new CommentSanitizer($this->comment);
    }

    public function test_constructor_should_set_comment_property()
    {
        $this->assertSame($this->comment, $this->getObjectValue($this->sanitizer, 'comment'));
    }

    public function test_sanitize_strips_h1_tags()
    {
        $sanitized = $this->sanitizer->sanitize();
        $this->assertFalse(strpos($sanitized, '<h1>Greetings</h1>'));
    }

    public function test_sanitize_strips_anchor_tags()
    {
        $sanitized = $this->sanitizer->sanitize();
        $this->assertFalse(strpos($sanitized, '<a href="#">here</a>'));
    }

    public function test_sanitize_strips_strong_tags()
    {
        $sanitized = $this->sanitizer->sanitize();
        $this->assertFalse(strpos($sanitized, '<strong>here</strong>'));
    }

    public function test_sanitize_strips_all_tags()
    {
        $stripped = strip_tags($this->comment->getText());
        $sanitized = $this->sanitizer->sanitize();
        $this->assertEquals($stripped, $sanitized);
    }
}
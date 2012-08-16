<?php
namespace Test\Unit\Presentation\Models\Input;
use Test\TestBase;
use Presentation\Models\Input\User;
class UserTest extends TestBase
{
    protected $data;

    public function setUp()
    {
        $this->data = ['username' => 'brian', 'password' => 'pass', 'passwordConfirm' => 'pass'];
    }

    public function test_isValid_should_return_false_if_username_omitted()
    {
        $this->data['username'] = null;
        $input = new User($this->data);

        $valid = $input->isValid();

        $this->assertFalse($valid);
    }

    public function test_isValid_should_return_false_if_password_omitted()
    {
        $this->data['password'] = null;
        $input = new User($this->data);

        $valid = $input->isValid();

        $this->assertFalse($valid);
    }

    public function test_isValid_should_return_false_if_passwordConfirm_omitted()
    {
        $this->data['passwordConfirm'] = null;
        $input = new User($this->data);

        $valid = $input->isValid();

        $this->assertFalse($valid);
    }

    public function test_isValid_should_return_true_if_all_fields_present()
    {
        $input = new User($this->data);

        $valid = $input->isValid();

        $this->assertTrue($valid);
    }

    public function test_should_have_error_message_for_username_if_username_is_empty()
    {
        $this->data['username'] = null;
        $input = new User($this->data);

        $input->isValid();

        $this->assertNotEmpty($input->getMessage('username'));
    }
}
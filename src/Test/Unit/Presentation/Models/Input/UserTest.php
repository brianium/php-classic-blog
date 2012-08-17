<?php
namespace Test\Unit\Presentation\Models\Input;
use Test\TestBase;
use Presentation\Models\Input\User;
class UserTest extends TestBase
{
    protected $data;
    protected $input;

    public function setUp()
    {
        $this->data = ['username' => 'brian', 'password' => 'pass', 'passwordConfirm' => 'pass'];
        $this->input = new User($this->data);
    }

    public function test_isValid_should_return_false_if_username_omitted()
    {
        $input = $this->getInput(['username' => null]);

        $valid = $input->isValid();

        $this->assertFalse($valid);
    }

    public function test_isValid_should_return_false_if_password_omitted()
    {
        $input = $this->getInput(['password' => null]);

        $valid = $input->isValid();

        $this->assertFalse($valid);
    }

    public function test_isValid_should_return_false_if_passwordConfirm_omitted()
    {
        $input = $this->getInput(['passwordConfirm' => null]);

        $valid = $input->isValid();

        $this->assertFalse($valid);
    }

    public function test_isValid_should_return_true_if_all_fields_present()
    {
        $valid = $this->input->isValid();

        $this->assertTrue($valid);
    }

    public function test_isValid_should_return_false_if_username_greather_than_50_chars()
    {
        $this->setLongUsername();
        $input = new User($this->data);

        $valid = $input->isValid();

        $this->assertFalse($valid);
    }

    public function test_unknown_property_is_empty()
    {
        $this->assertEmpty($this->input->sandwich);
    }

    public function test_known_property_is_returned()
    {
        $this->assertEquals('brian', $this->input->username);
    }

    public function test_should_have_error_message_for_username_if_username_is_empty()
    {
        $input = $this->getInput(['username' => null]);

        $input->isValid();

        $this->assertNotEmpty($input->getMessageFor('username'));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function test_error_message_not_set_before_isValid_called()
    {
        $input = $this->getInput(['username' => null]);

        $this->assertEmpty($input->getMessageFor('username'));
    }

    public function test_getMessage_returns_empty_for_unkown_property()
    {
        $this->input->isValid();

        $this->assertEmpty($this->input->getMessageFor('hamsandwich'));
    }

    public function test_constructed_with_error_array_sets_message_by_key_when_one()
    {
        $msg = 'Please provide a username'; 
        $input = $this->getInput(['username' => null], ['username.nonEmpty' => $msg]);

        $input->isValid();

        $this->assertEquals($msg, $input->getMessageFor('username'));
    }

    public function test_constructed_with_error_array_sets_message_by_key_when_two()
    {
        $msgNonEmpty = 'Please provide a username'; 
        $msgAtMost = 'Username cannot be longer than 50 chars';
        $this->setLongUsername(); 
        $input = $this->getInput([], ['username.nonEmpty' => $msgNonEmpty, 'username.atMostChars' => $msgAtMost]);

        $input->isValid();

        $this->assertEquals($msgAtMost, $input->getMessageFor('username'));
    }

    public function test_non_matching_password_returns_error()
    {
        $input = $this->getInput(['passwordConfirm' => 'nope']);
    }

    public function test_setRepository_should_set_repository_property_to_UserRepository()
    {
        $repo = $this->getMock('Domain\\Repositories\\IUserRepository');
        $this->input->setRepository($repo);

        $this->assertInstanceOf('Domain\\Repositories\\IUserRepository', $this->getObjectValue($this->input, 'repository'));
    }

    public function test_setRepository_should_return_self()
    {
        $repo = $this->getMock('Domain\\Repositories\\IUserRepository');
        $self = $this->input->setRepository($repo);

        $this->assertSame($this->input, $self);
    }

    protected function setLongUsername()
    {
        $username = '';
        for($i = 1; $i <= 51; $i++)
            $username .= 'a';
        $this->data['username'] = $username;
    }

    protected function getInput($vals, $msgs = [])
    {
        $this->data = array_merge($this->data, $vals);
        return new User($this->data, $msgs);
    }
}
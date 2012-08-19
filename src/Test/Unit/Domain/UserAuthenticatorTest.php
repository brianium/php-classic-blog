<?php
namespace Test\Unit\Domain;
use Test\TestBase;
use Domain\UserAuthenticator;
use Domain\PasswordHasher;
class UserAuthenticatorTest extends TestBase
{
    protected $user;
    protected $repo;
    protected $hasher;

    public function setUp()
    {
        parent::setUp();
        $this->user = $this->loadFixture('Test\\Fixtures\\User\\UserNoPosts', 'Domain\\Entities\\User');
        $this->repo = $this->getMock('Domain\\Repositories\\UserRepository');
        $this->hasher = $this->getMock('Domain\\PasswordHasher');
        $this->authenticator = new UserAuthenticator($this->repo, $this->hasher);
    }

    public function test_constructor_should_set_repo_to_UserRepository()
    {
        $this->assertInstanceOf('Domain\\Repositories\\UserRepository', $this->getObjectValue($this->authenticator, 'repo'));        
    }

    public function test_constructor_should_set_hasher_to_PasswordHasher()
    {
        $this->assertInstanceOf('Domain\\PasswordHasher', $this->getObjectValue($this->authenticator, 'hasher'));
    }

    public function test_isAuthenticated_calls_repo_getBy()
    {
        $this->getStubbedRepo()
             ->with($this->equalTo(['username' => $this->user->getUsername()]));

        $this->authenticator->isAuthenticated($this->user->getUsername(), "password");
    }

    public function test_isAuthenticated_returns_false_when_user_not_found()
    {
        $this->getStubbedRepo()
             ->with($this->anything())
             ->will($this->returnValue(false));

        $this->assertFalse($this->authenticator->isAuthenticated($this->user->getUsername(), 'password'));
    }

    public function test_isAuthenticated_returns_false_when_hasher_check_fails()
    {
        $this->getStubbedRepo()
             ->will($this->returnValue([$this->user]));

        $this->getStubbedHasher()
             ->will($this->returnValue(false));

        $this->assertFalse($this->authenticator->isAuthenticated($this->user->getUsername(), 'password'));
    }

    public function test_isAuthenticated_returns_true_when_hasher_check_passes()
    {
        $this->getStubbedRepo()
             ->will($this->returnValue([$this->user]));

        $this->getStubbedHasher()
             ->will($this->returnValue(true));

        $this->assertTrue($this->authenticator->isAuthenticated($this->user->getUsername(), 'password'));
    }

    public function test_hashPassword_sets_user_password_to_hashed_version()
    {
        $this->hasher->expects($this->once())
                     ->method('hash')
                     ->with($this->user->getPassword())
                     ->will($this->returnValue('sandwich'));

        $this->authenticator->hashPassword($this->user);

        $this->assertEquals('sandwich', $this->user->getPassword());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function test_initNewUser_throws_exception_if_user_is_not_new()
    {
        $this->authenticator->initNewUser($this->user);
    }

    public function test_initNewUser_hashes_passed_users_password_and_refreshs_user()
    {
        $this->setObjectValue($this->user, 'id', null);
        $this->hasher->expects($this->once())
                     ->method('hash')
                     ->with($this->user->getPassword());

        $currentProps = [$this->user->getTimeout(), $this->user->getToken(), $this->user->getIdentifier()];

        $this->authenticator->initNewUser($this->user);

        $newProps = [$this->user->getTimeout(), $this->user->getToken(), $this->user->getIdentifier()];

        $this->assertNotEquals($currentProps, $newProps);
    }

    protected function getStubbedHasher()
    {
        return $this->hasher->expects($this->once())
                     ->method('checkPassword')
                     ->with($this->user->getPassword(), $this->anything());
    }

    protected function getStubbedRepo()
    {
        return $this->repo->expects($this->once())
                          ->method('getBy');
    }
}
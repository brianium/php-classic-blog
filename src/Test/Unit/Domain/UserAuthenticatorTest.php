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
        $this->authenticator = new UserAuthenticator($this->user, $this->repo, $this->hasher);
    }

    public function test_constructor_should_set_user_property()
    {
        $this->assertSame($this->user, $this->getObjectValue($this->authenticator, 'user'));
    }

    public function test_constructor_should_set_repo_to_UserRepository()
    {
        $this->assertInstanceOf('Domain\\Repositories\\UserRepository', $this->getObjectValue($this->authenticator, 'repo'));        
    }

    public function test_constructor_should_set_hasher_to_PasswordHasher()
    {
        $this->assertInstanceOf('Domain\\PasswordHasher', $this->getObjectValue($this->authenticator, 'hasher'));
    }

    public function test_isAuthenticated_calls_repo_contains()
    {
        $this->getStubbedRepo()
             ->with($this->equalTo($this->user));

        $this->authenticator->isAuthenticated("password");
    }

    public function test_isAuthenticated_returns_false_when_user_not_found()
    {
        $this->getStubbedRepo()
             ->with($this->anything())
             ->will($this->returnValue(false));

        $this->assertFalse($this->authenticator->isAuthenticated('password'));
    }

    public function test_isAuthenticated_returns_false_when_hasher_check_fails()
    {
        $this->getStubbedRepo()
             ->will($this->returnValue(true));

        $this->getStubbedHasher()
             ->will($this->returnValue(false));

        $this->assertFalse($this->authenticator->isAuthenticated('password'));
    }

    public function test_isAuthenticated_returns_true_when_hasher_check_passes()
    {
        $this->getStubbedRepo()
             ->will($this->returnValue(true));

        $this->getStubbedHasher()
             ->will($this->returnValue(true));

        $this->assertTrue($this->authenticator->isAuthenticated('password'));
    }

    public function test_refreshTimeout_sets_user_timeout_to_now_plus_one_week_default()
    {
        $time = new \DateTime('now');
        $time->add(new \DateInterval('P1W'));

        $this->authenticator->refreshTimeout();

        $this->assertEquals($time->getTimestamp(), $this->user->getTimeout());
    }

    public function test_refreshTimeout_sets_user_timeout_to_now_plus_specified_interval()
    {
        $time = new \DateTime('now');
        $interval = new \DateInterval('P3W');
        $time->add($interval);

        $this->authenticator->refreshTimeout($interval);

        $this->assertEquals($time->getTimestamp(), $this->user->getTimeout());
    }

    public function test_refreshIdentifier_sets_user_identifier_to_different_value()
    {
        $currentId = $this->user->getIdentifier();

        $this->authenticator->refreshIdentifier();

        $this->assertNotEquals($currentId, $this->user->getIdentifier());
    }

    public function test_refreshToken_sets_user_token_to_different_value()
    {
        $currentToken = $this->user->getToken();

        $this->authenticator->refreshToken();

        $this->assertNotEquals($currentToken, $this->user->getToken());
    }

    public function test_hashPassword_sets_user_password_to_hashed_version()
    {
        $this->hasher->expects($this->once())
                     ->method('hash')
                     ->with($this->user->getPassword())
                     ->will($this->returnValue('sandwich'));

        $this->authenticator->hashPassword();

        $this->assertEquals('sandwich', $this->user->getPassword());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function test_initNewUser_throws_exception_if_user_is_not_new()
    {
        $this->authenticator->initNewUser();
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
                          ->method('contains');
    }
}
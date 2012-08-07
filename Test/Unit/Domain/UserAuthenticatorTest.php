<?php
namespace Test\Unit\Domain;
use Test\Unit\UnitTestBase;
use Domain\UserAuthenticator;
class UserAuthenticatorTest extends UnitTestBase
{

    protected $user;

    public function setUp()
    {
        parent::setUp();
        $this->user = $this->loadFixture('Test\\Fixtures\\User\\UserNoPosts', 'Domain\\Entities\\User');
    }

    public function test_constructor_should_set_user_property()
    {
        $authenticator = new UserAuthenticator($this->user);
        $this->assertSame($this->user, $this->getObjectValue($authenticator, 'user'));
    }
}
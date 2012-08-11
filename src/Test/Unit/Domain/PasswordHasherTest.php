<?php
namespace Test\Unit\Domain;
use Domain\PasswordHasher;
use Test\TestBase;
class PasswordHasherTest extends TestBase
{
    protected $hasher;

    public function setUp()
    {
        $this->hasher = new PasswordHasher();
    }

    public function test_getUniqueSalt_should_return_22_length_string()
    {
        $salt = PasswordHasher::getUniqueSalt();
        $this->assertEquals(22, strlen($salt));
    }

    public function test_two_successive_salts_are_not_equal()
    {
        $salt1 = PasswordHasher::getUniqueSalt();
        $salt2 = PasswordHasher::getUniqueSalt();
        $this->assertFalse($salt1 == $salt2);
    }

    public function test_constructor_should_set_cost()
    {
        $hasher = new PasswordHasher(5);
        $this->assertEquals(5, $this->getObjectValue($hasher, 'cost'));
    }

    public function test_cost_should_default_to_10()
    {
        $this->assertEquals(10, $this->getObjectValue($this->hasher, 'cost'));
    }

    public function test_hash_should_return_string_containing_algo_and_cost()
    {
        $plainText = 'password';
        $hashed = $this->hasher->hash($plainText);
        $this->assertTrue(strpos($hashed, '$2y$10$') !== false);
    }

    public function test_cost_should_be_converted_to_string_with_leading_zero_if_single_digit()
    {
        $hasher = new PasswordHasher(7);
        $hashed = $hasher->hash('password');
        $algoCost = substr($hashed, 0, 7);
        $this->assertEquals('$2y$07$', $algoCost);  
    }

    public function test_checkPassword_should_return_true_for_same_password()
    {
        $plainText = 'hello';
        $hashed = $this->hasher->hash($plainText);
        $this->assertTrue($this->hasher->checkPassword($hashed, $plainText));
    }

    public function test_checkPassword_should_return_false_for_different_password()
    {
        $hashed = $this->hasher->hash('zooom');
        $this->assertFalse($this->hasher->checkPassword($hashed, 'kabooom'));
    }
}
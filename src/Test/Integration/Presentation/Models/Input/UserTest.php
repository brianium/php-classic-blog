<?php
namespace Test\Integration\Presentation\Models\Input;
use Test\TestBase;
use Infrastructure\Persistence\Doctrine\UserRepository;
use Infrastructure\Persistence\Doctrine\EntityManagerFactory;
class UserTest extends TestBase
{
    protected $repo;

    public function setUp()
    {
        parent::setUp();
        $manager = EntityManagerFactory::getNewManager();
        $this->repo = new UserRepository($manager);
    }

    public function testassertTruth()
    {
        $this->assertTrue(true);
    }
}
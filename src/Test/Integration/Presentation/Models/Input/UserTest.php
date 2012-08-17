<?php
namespace Test\Integration\Presentation\Models\Input;
use Test\TestBase;
use Infrastructure\Persistence\Doctrine\UserRepository;
use Infrastructure\Persistence\Doctrine\EntityManagerFactory;
use Test\Integration\Infrastructure\Persistence\Doctrine\DataTester;
class UserTest extends TestBase
{
    use DataTester;
    protected $repo;
    protected $input;

    public function setUp()
    {
        parent::setUp();
        $manager = EntityManagerFactory::getNewManager();
        $this->createSchema($manager);
        $this->repo = new UserRepository($manager);
        $user = $this->loadFixture('Test\\Fixtures\\User\\UserNoPosts', 'Domain\\Entities\\User');
        $this->repo->store($user->getAsUser());
        $this->input = $this->loadFixture('Test\\Fixtures\\UserInput\\UserNoPostsInput', 'Presentation\\Models\\Input\\User');
    }

    public function tearDown()
    {
        $this->dropSchema();
    }

    public function test_isValid_should_return_false_when_repo_set_and_user_exists()
    {
        $this->input->setRepository($this->repo);

        $this->assertFalse($this->input->isValid());
    }

    public function test_isValid_should_return_true_when_when_repo_set_and_user_doesnt_exsit()
    {
        $this->input->setRepository($this->repo);
        $this->input->username = "sofreshandsoclean";

        $this->assertTrue($this->input->isValid());
    }
}
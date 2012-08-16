<?php
namespace Test\Integration\Infrastructure\Persistence\Doctrine;
use Test\TestBase;
use Infrastructure\Persistence\Doctrine\UnitOfWork;
class UnitOfWorkTest extends TestBase
{
    protected $uow;

    public function setUp()
    {
        parent::setUp();
        $this->uow = new UnitOfWork();
    }

    public function test_begin_should_set_connection_if_not_set()
    {
        $this->uow->begin();

        $this->assertInstanceOf('Doctrine\\DBAL\\Connection', $this->getObjectValue($this->uow, 'connection'));
    }
} 
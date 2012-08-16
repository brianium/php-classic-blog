<?php
namespace Test\Unit\Infrastructure\Persistence\Doctrine;
use Test\TestBase;
use Infrastructure\Persistence\Doctrine\UnitOfWork;
class UnitOfWorkTest extends TestBase
{
    protected $uow;
    protected $connection;

    public function setUp()
    {
        parent::setUp();
        $this->uow = new UnitOfWork();
        $this->connection = $this->getMockBuilder('Doctrine\\DBAL\\Connection')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->uow->setConnection($this->connection);
    }

    public function test_setConnection_should_set_connection_property_to_DoctrineDBALConnection()
    {
        $this->assertInstanceOf('Doctrine\\DBAL\\Connection', $this->getObjectValue($this->uow, 'connection'));
    }

    public function test_begin_should_call_connection_beginTransaction()
    {
        $this->connection->expects($this->once())
             ->method('beginTransaction');

        $this->uow->begin();
    }

    public function test_commit_should_call_connection_commit()
    {
        $this->connection->expects($this->once())
             ->method('commit');

        $this->uow->commit();
    }

    public function test_rollback_should_call_connection_rollback()
    {
        $this->connection->expects($this->once())
             ->method('rollback');

        $this->uow->rollback();
    }
}
<?php
namespace Infrastructure\Persistence\Doctrine;
use Doctrine\DBAL\Connection;
class UnitOfWork
{
    protected $connection;

    public function setConnection(Connection $conn)
    {
        $this->connection = $conn;
    }

    public function begin()
    {
        if(is_null($this->connection))
            $this->connection = EntityManagerFactory::getNewManager()->getConnection();

        $this->connection->beginTransaction();
    }

    public function commit()
    {
        $this->connection->commit();
    }

    public function rollback()
    {
        $this->connection->rollback();
    }
}
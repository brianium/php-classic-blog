<?php
namespace Infrastructure\Persistence\Doctrine;
use Infrastructure\Persistence\Doctrine\ConfigurationFactory;
use Doctrine\ORM\EntityManager;
class EntityManagerFactory
{
    private static $manager;

    private static function initManager()
    {
        $configFactory = new ConfigurationFactory();
        $dbParams = self::getDbParams();
        self::$manager = EntityManager::create($dbParams, $configFactory->build());
    }

    public static function getManager()
    {
        if(is_null(self::$manager))
            self::initManager();

        return self::$manager;
    }

    private static function getDbParams()
    {
        $params = ['user' => 'root', 'driver' => 'pdo_sqlite',
                   'dbname' => 'blog.test', 'memory' => true];
                   
        return $params;
    }
}
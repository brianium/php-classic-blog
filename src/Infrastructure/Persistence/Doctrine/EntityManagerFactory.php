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
        $json = dirname(__FILE__) . DS . 'doctrine.cfg.json';
        $configs = json_decode(file_get_contents($json));

        $paramsKey = (getenv('APPLICATION_ENV') == 'development') ? 'development' : 'production';
        $params = $configs->params->{$paramsKey};
                   
        return get_object_vars($params);
    }
}
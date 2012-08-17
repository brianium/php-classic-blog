<?php
namespace Infrastructure\Persistence\Doctrine;
use Infrastructure\Persistence\Doctrine\ConfigurationFactory;
use Doctrine\ORM\EntityManager;
class EntityManagerFactory
{
    private static $singleton;

    public static function getNewManager()
    {
        $configFactory = new ConfigurationFactory();
        $dbParams = self::getDbParams();
        return EntityManager::create($dbParams, $configFactory->build());
    }

    public static function getDbParams()
    {
        $json = dirname(__FILE__) . DS . 'doctrine.cfg.json';
        $configs = json_decode(file_get_contents($json));

        $paramsKey = (getenv('APPLICATION_ENV') == 'development') ? 'development' : 'production';

        if(!property_exists($configs->params, $paramsKey))
            return [];
                   
        return get_object_vars($configs->params->{$paramsKey});
    }

    public static function getSingleton()
    {
        if(is_null(self::$singleton))
            self::$singleton = self::getNewManager();

        return self::$singleton;
    }
}
<?php
namespace Infrastructure\Persistence\Doctrine;
use Doctrine\ORM\Tools\Setup;
class ConfigurationFactory
{
    public function buildDevConfig()
    {
        $paths = [APP_SRC . DS . 'Infrastructure' . DS . 'Persistence' . DS . 'Doctrine' . DS . 'mappings'];   
        $isDevMode = true;
        $config = Setup::createXMLMetadataConfiguration($paths, $isDevMode);
        return $config;
    }
}
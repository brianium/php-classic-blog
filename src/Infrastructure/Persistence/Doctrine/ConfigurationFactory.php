<?php
namespace Infrastructure\Persistence\Doctrine;
use Doctrine\ORM\Tools\Setup;
class ConfigurationFactory
{
    public function __construct() {
        $this->paths = [APP_SRC . DS . 'Infrastructure' . DS . 'Persistence' . DS . 'Doctrine' . DS . 'mappings'];
    }

    public function build()
    {
        if(getenv('APPLICATION_ENV') == 'development')
            return $this->buildDevConfig();
        
        return $this->buildProdConfig();
    }

    public function buildDevConfig()
    {
        return Setup::createXMLMetadataConfiguration($this->paths, true);
    }

    public function buildProdConfig()
    {
        $proxies = dirname(__FILE__) . DS . 'proxies';
        return Setup::createXMLMetadataConfiguration($this->paths, false, $proxies);
    }
}
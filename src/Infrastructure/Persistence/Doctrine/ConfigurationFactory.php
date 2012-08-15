<?php
namespace Infrastructure\Persistence\Doctrine;
use Doctrine\ORM\Tools\Setup;
class ConfigurationFactory
{
    public function __construct() {
        $this->paths = [APP_SRC . DS . 'Infrastructure' . DS . 'Persistence' . DS . 'Doctrine' . DS . 'mappings'];
    }

    public function buildDevConfig()
    {
        return Setup::createXMLMetadataConfiguration($this->paths, true);
    }

    public function buildProdConfig()
    {
        return Setup::createXMLMetadataConfiguration($this->paths, false);
    }
}
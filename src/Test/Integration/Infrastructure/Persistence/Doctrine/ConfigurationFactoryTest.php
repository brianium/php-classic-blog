<?php
namespace Test\Integration\Infrastructure\Persistence\Doctrine;
use Test\TestBase;
use Infrastructure\Persistence\Doctrine\ConfigurationFactory;
class ConfigurationFactoryTest extends TestBase
{
    protected $factory;
    protected $devConfig;

    public function setUp()
    {
        $this->factory = new ConfigurationFactory();
        $this->devConfig = $this->factory->buildDevConfig();
    }

    public function test_buildDevConfig_should_return_config_with_ArrayCache_for_MetadataCache()
    {
        $this->assertInstanceOf('Doctrine\\Common\\Cache\\ArrayCache', $this->devConfig->getMetadataCacheImpl());
    }

    public function test_buildDevConfig_should_return_config_with_ArrayCache_for_QueryCache()
    {
        $this->assertInstanceOf('Doctrine\\Common\\Cache\\ArrayCache', $this->devConfig->getQueryCacheImpl());
    }

    public function test_buildDevConfig_should_return_config_with_ArrayCache_for_ResultCache()
    {
        $this->assertInstanceOf('Doctrine\\Common\\Cache\\ArrayCache', $this->devConfig->getResultCacheImpl());
    }

    public function test_buildDevConfig_should_return_config_with_ProxyDir_set_to_temp()
    {
        $this->assertEquals(sys_get_temp_dir(), $this->devConfig->getProxyDir());
    }
}
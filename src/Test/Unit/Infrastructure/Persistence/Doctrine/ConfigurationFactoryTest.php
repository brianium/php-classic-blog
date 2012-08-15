<?php
namespace Test\Unit\Infrastructure\Persistence\Doctrine;
use Test\TestBase;
use Infrastructure\Persistence\Doctrine\ConfigurationFactory;
class ConfigurationFactoryTest extends TestBase
{
    protected $factory;
    protected $devConfig;
    protected $prodConfig;

    public function setUp()
    {
        parent::setUp();
        $this->factory = new ConfigurationFactory();
        $this->devConfig = $this->factory->buildDevConfig();
        $this->prodConfig = $this->factory->buildProdConfig();
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

    public function test_buildDevConfig_should_return_config_with_auto_proxy_classes()
    {
        $this->assertTrue($this->devConfig->getAutoGenerateProxyClasses());
    }

    public function test_buildProdConfig_should_return_config_with_ApcCache_for_MetadataCache()
    {
        $this->assertInstanceOf('Doctrine\\Common\\Cache\\ApcCache', $this->prodConfig->getMetadataCacheImpl());
    }

    public function test_buildProdConfig_should_return_config_with_ApcCache_for_QueryCache()
    {
        $this->assertInstanceOf('Doctrine\\Common\\Cache\\ApcCache', $this->prodConfig->getQueryCacheImpl());
    }

    public function test_buildDevConfig_should_return_config_with_ApcCache_for_ResultCache()
    {
        $this->assertInstanceOf('Doctrine\\Common\\Cache\\ApcCache', $this->prodConfig->getResultCacheImpl());
    }

    public function test_buildProdConfig_should_return_config_without_auto_proxy_classes()
    {
        $this->assertFalse($this->prodConfig->getAutoGenerateProxyClasses());
    }

    public function test_buildProdConfig_should_return_config_with_ProxyDir_set_to_proxies()
    {
        $proxies = APP_SRC . DS . 'Infrastructure' . DS . 'Persistence' . DS . 'Doctrine' . DS . 'proxies';
        $this->assertEquals($proxies, $this->prodConfig->getProxyDir());
    }

    public function test_build_with_APPLICATION_ENV_set_to_development_returns_dev_config()
    {
        $config = $this->factory->build();

        $this->assertEquals($this->devConfig, $config);
    }

    public function test_build_with_APPLICATION_ENV_not_set_to_development_returns_prod_config()
    {
        putenv('APPLICATION_ENV');

        $config = $this->factory->build();

        $this->assertEquals($this->prodConfig, $config);
    }
}
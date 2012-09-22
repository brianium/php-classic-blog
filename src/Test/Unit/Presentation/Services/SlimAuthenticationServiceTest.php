<?php
namespace Test\Unit\Presentation\Services;
use Test\TestBase;
use Presentation\Services\SlimAuthenticationService;

class SlimAuthenticationServiceTest extends TestBase
{
    protected $slim;
    protected $request;

    protected $userRepo;
    protected $service;

    public function setUp()
    {
        $this->slim = $this->getMockBuilder('Slim')
                           ->disableOriginalConstructor()
                           ->getMock();
        //stub slim object
        $this->request = $this->getMockBuilder('Slim_Http_Request')
                              ->disableOriginalConstructor()
                              ->getMock();
        
        $this->slim->expects($this->any())
                   ->method('request')
                   ->will($this->returnValue($this->request));

        $this->userRepo = $this->getMock('Domain\\Repositories\\UserRepository');

        $this->service = new SlimAuthenticationService($this->slim, $this->userRepo);
        $this->service->addRoute('admin');
    }

    public function test_constructor()
    {
        $service = new SlimAuthenticationService($this->slim, $this->userRepo);
        $this->assertInstanceOf('Slim', $this->getObjectValue($service, 'slim'));
        $this->assertInstanceOf('Domain\\Repositories\\UserRepository', $this->getObjectValue($service, 'userRepo'));
        $this->assertEmpty($this->getObjectValue($service, 'routes'));
    }

    public function test_addRoute_should_add_route_to_internal_collection()
    {
        $service = new SlimAuthenticationService($this->slim, $this->userRepo);
        $service->addRoute("admin");

        $this->assertEquals(['admin'], $this->getObjectValue($service, 'routes'));
    }

    public function test_addRoute_should_return_self()
    {
        $service = new SlimAuthenticationService($this->slim, $this->userRepo);
        $this->assertSame($service, $service->addRoute('admin'));
    }

    public function test_isAuthenticated_should_return_true_when_no_routes()
    {
        $service = new SlimAuthenticationService($this->slim, $this->userRepo);
        $this->assertTrue($service->isAuthenticated('cookiename'));
    }

    public function test_isAuthenticated_returns_false_for_null_cookie_on_current_path()
    {
        $this->request->expects($this->once())
                      ->method('getPath')
                      ->will($this->returnValue('/admin'));

        $this->slim->expects($this->once())
                   ->method('getCookie')
                   ->with($this->identicalTo('cookiename'))
                   ->will($this->returnValue(null));

        $this->assertFalse($this->service->isAuthenticated('cookiename'));
    }
}
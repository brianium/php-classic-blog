<?php
namespace Test\Unit\Presentation\Services;
use Test\TestBase;
use Presentation\Services\SlimAuthenticationService;

class SlimAuthenticationServiceTest extends TestBase
{
    protected $slim;
    protected $request;

    protected $userRepo;
    protected $userAuth;
    protected $user;

    protected $service;

    public function setUp()
    {
        parent::setUp();
        $this->slim = $this->getMockSlim();
        //stub slim object
        $this->request = $this->getMockRequest();

        $this->request->expects($this->any())
                      ->method('getPath')
                      ->will($this->returnValue('/admin'));
        
        $this->slim->expects($this->any())
                   ->method('request')
                   ->will($this->returnValue($this->request));

        $this->userRepo = $this->getMock('Domain\\Repositories\\UserRepository');
        $this->userAuth = $this->getMockAuthenticator();
        $this->user = $this->loadFixture('Test\\Fixtures\\User\\UserNoPosts', 'Domain\\Entities\\User');

        $this->service = new SlimAuthenticationService($this->slim, $this->userRepo, $this->userAuth);
        $this->service->addRoute('admin');
    }

    public function test_constructor()
    {
        $service = new SlimAuthenticationService($this->slim, $this->userRepo, $this->userAuth);
        $this->assertInstanceOf('Slim', $this->getObjectValue($service, 'slim'));
        $this->assertInstanceOf('Domain\\Repositories\\UserRepository', $this->getObjectValue($service, 'userRepo'));
        $this->assertInstanceOf('Domain\\UserAuthenticator', $this->getObjectValue($service, 'userAuth'));
        $this->assertEmpty($this->getObjectValue($service, 'routes'));
    }

    public function test_addRoute_should_add_route_to_internal_collection()
    {
        $service = new SlimAuthenticationService($this->slim, $this->userRepo, $this->userAuth);
        $service->addRoute("admin");

        $this->assertEquals(['admin'], $this->getObjectValue($service, 'routes'));
    }

    public function test_addRoute_should_return_self()
    {
        $service = new SlimAuthenticationService($this->slim, $this->userRepo, $this->userAuth);
        $this->assertSame($service, $service->addRoute('admin'));
    }

    public function test_isAuthenticated_should_return_true_when_no_routes()
    {
        $service = new SlimAuthenticationService($this->slim, $this->userRepo, $this->userAuth);
        $this->assertTrue($service->isAuthenticated('cookiename'));
    }

    public function test_isAuthenticated_should_not_check_users_when_no_routes()
    {
        $service = new SlimAuthenticationService($this->slim, $this->userRepo, $this->userAuth);

        $this->userRepo->expects($this->never())
                       ->method('getBy');

        $this->assertTrue($service->isAuthenticated('cookiename'));
    }

    public function test_isAuthenticated_returns_true_when_cookie_present_but_route_not_secured()
    {
        $request = $this->getMockRequest();
        $request->expects($this->any())
                      ->method('getPath')
                      ->will($this->returnValue('/doesNotRequireAuth'));

        $slim = $this->getMockSlim();
        $slim->expects($this->once())
             ->method('request')
             ->will($this->returnValue($request));

        $this->cookieReturnsValidCookie($slim);

        $service = new SlimAuthenticationService($slim, $this->userRepo, $this->userAuth);
        $service->addRoute('admin');

        $this->assertTrue($service->isAuthenticated('cookiename'));
    }

    public function test_isAuthenticated_returns_false_for_null_cookie_on_current_path()
    {
        $this->cookieReturnsNull();
        $this->assertFalse($this->service->isAuthenticated('cookiename'));
    }

    public function test_isAuthenticated_returns_false_when_token_invalid()
    {
        $this->cookieReturnsInvalidToken();
        $this->userRepoReturnsUserFixture();
        $this->assertFalse($this->service->isAuthenticated('cookiename'));               
    }

    public function test_isAuthenticated_returns_false_when_no_users_found()
    {
        $this->cookieReturnsValidCookie();
        $this->userRepoReturnsEmptyArray();
        $this->assertFalse($this->service->isAuthenticated('cookiename'));
    }

    public function test_isAuthenticated_returns_false_when_now_greater_than_user_timeout()
    {
        $this->cookieReturnsValidCookie();
        $this->user->setTimeout(strtotime("-1 week"));
        $this->userRepoReturnsUserFixture();
        $this->assertFalse($this->service->isAuthenticated('cookiename'));
    }

    public function test_isAuthenticated_returns_true_when_valid_token_and_timeout()
    {
        $this->cookieReturnsValidCookie();
        $this->user->setTimeout(strtotime("+1 week"));
        $this->userRepoReturnsUserFixture();
        $this->assertTrue($this->service->isAuthenticated('cookiename'));
    }

    public function test_matchesCurrentRoute_should_match_pattern()
    {
        $match = $this->service->matchesCurrentRoute('/^\/ad.*/');
        $this->assertTrue($match);
    }

    public function test_setAuthCookie_sets_cookie_to_user_token_string()
    {
        $this->slim->expects($this->once())
                   ->method('setCookie')
                   ->with($this->identicalTo('cookiename', $this->user->getTokenString()));

        $this->service->setAuthCookie('cookiename', $this->user);
    }

    public function test_login_should_refresh_user()
    {
        $user = $this->loadFixture('Test\\Fixtures\\User\\UserNoPosts', 'Domain\\Entities\\User');
        $this->service->login($this->user, 'c');
        $this->assertNotEquals($user->getTimeout(), $this->user->getTimeout());
        $this->assertNotEquals($user->getIdentifier(), $this->user->getIdentifier());
        $this->assertNotEquals($user->getToken(), $this->user->getToken());
    }

    public function test_login_should_save_refreshed_user()
    {
        $this->userRepo->expects($this->once())
                       ->method('store')
                       ->with($this->user);

        $this->service->login($this->user, 'c');
    }

    public function test_login_sets_auth_cookie()
    {
        $this->slim->expects($this->once())
                   ->method('setCookie');

        $this->service->login($this->user, 'cookiename');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function test_login_accepts_callable()
    {
        $this->service->login($this->user, 'c', function() {
            throw new \RuntimeException("function called");
        });
    }

    public function test_register_hashesPassword()
    {
        $this->userAuth->expects($this->once())
                       ->method('hashPassword');

        $this->service->register($this->user, 'c');
    }

    public function test_canLogin_calls_userAuth_isAuthenticated()
    {
        $this->userAuth->expects($this->once())
                       ->method('isAuthenticated');

        $this->service->canLogin('username', 'password');
    }

    public function test_getLoggedInUser_should_call_repo_getBy_with_identifier_in_cookie()
    {
        $this->cookieReturnsValidCookie();

        $this->userRepoReturnsUserFixture();

        $user = $this->service->getLoggedInUser('cookiename');

        $this->assertEquals($this->user, $user);
    }

    public function test_getLoggedInUser_returns_null_when_cookie_is_null()
    {
        $this->cookieReturnsNull();

        $this->assertNull($this->service->getLoggedInUser("cookiename"));
    }

    protected function cookieReturnsInvalidIdentifier()
    {
        $this->slim->expects($this->once())
                   ->method('getCookie')
                   ->with($this->identicalTo('cookiename'))
                   ->will($this->returnValue('notfound' . ':' . $this->user->getToken()));
    }

    protected function userRepoReturnsEmptyArray()
    {
        $this->userRepo->expects($this->once())
                       ->method('getBy')
                       ->will($this->returnValue([]));
    }

    protected function userRepoReturnsUserFixture()
    {
        $this->userRepo->expects($this->once())
                       ->method('getBy')
                       ->with($this->identicalTo(['identifier' => $this->user->getIdentifier()]))
                       ->will($this->returnValue([$this->user]));
    }

    protected function cookieReturnsInvalidToken($value='')
    {
        $this->slim->expects($this->once())
                   ->method('getCookie')
                   ->with($this->identicalTo('cookiename'))
                   ->will($this->returnValue($this->user->getIdentifier() . ':' . 'invalidtoken'));
    }

    protected function cookieReturnsNull()
    {
        $this->slim->expects($this->once())
                   ->method('getCookie')
                   ->with($this->identicalTo('cookiename'))
                   ->will($this->returnValue(null));
    }

    protected function cookieReturnsValidCookie($slim = null)
    {
        if(is_null($slim)) $slim = $this->slim;
        $slim->expects($this->any())
                   ->method('getCookie')
                   ->with($this->identicalTo('cookiename'))
                   ->will($this->returnValue($this->user->getTokenString()));
    }

    protected function getMockRequest()
    {
        return $this->getMockBuilder('Slim_Http_Request')
                              ->disableOriginalConstructor()
                              ->getMock();
    }

    protected function getMockSlim()
    {
        return $this->getMockBuilder('Slim')
                           ->disableOriginalConstructor()
                           ->getMock();
    }

    protected function getMockAuthenticator() {
        return $this->getMockBuilder('Domain\\UserAuthenticator')
                    ->disableOriginalConstructor()
                    ->getMock();
    }
}
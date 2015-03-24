<?php

namespace United\OneBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class PlainControllerTest extends WebTestCase
{

    private $path = 'plancontrollertest';

    /**
     * @var Client $anonClient
     */
    private $anonClient;

    /**
     * @var Client $adminClient
     */
    private $adminClient;

    public function setUp()
    {
        parent::setUp();
        $this->anonClient = static::createClient();
        $this->adminClient = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));
    }

    private function p($path)
    {
        return 'http://' . $this->anonClient->getRequest()->getHost() . $path;
    }

    private function u($path = '', $auth = false)
    {
        $str = '/functional/';

        if($auth) {
            $str .= 'auth';
        } else {
            $str .= 'anon';
        }

        $str .= $path;
        return $str;
    }

    /**
     * Attempts to guess the kernel location.
     *
     * When the Kernel is located, the file is required.
     *
     * @return string The Kernel class name
     *
     * @throws \RuntimeException
     */
    protected static function getKernelClass()
    {
        require_once '../tests/AppKernel.php';
        return 'AppKernel';
    }

    public function testRedirectAction()
    {
        // base route redirect action should add a slash to the end of the url
        $this->anonClient->request('GET', $this->u());
        $this->assertEquals(301, $this->anonClient->getResponse()->getStatusCode());
        $this->assertTrue($this->anonClient->getResponse()->isRedirect($this->p($this->u('/'))));

        // base route should redirect to first controller
        $this->anonClient->request('GET', $this->u('/'));
        $this->assertEquals(301, $this->anonClient->getResponse()->getStatusCode());
        $this->assertTrue($this->anonClient->getResponse()->isRedirect($this->p($this->u('/' . $this->path . '/'))));

        // first controller without slash should add slash to the end of the url
        $this->anonClient->request('GET', $this->u('/' . $this->path));
        $this->assertEquals(301, $this->anonClient->getResponse()->getStatusCode());
        $this->assertTrue($this->anonClient->getResponse()->isRedirect($this->p($this->u('/' . $this->path . '/'))));

        // base route redirect action should add a slash to the end of the url, even if not logged in
        $this->anonClient->request('GET', $this->u('', true));
        $this->assertEquals(301, $this->anonClient->getResponse()->getStatusCode());
        $this->assertTrue($this->anonClient->getResponse()->isRedirect($this->p($this->u('/', true))));

        // base route should redirect to first controller, even if not logged in
        $this->anonClient->request('GET', $this->u('/', true));
        $this->assertEquals(301, $this->anonClient->getResponse()->getStatusCode());
        $this->assertTrue($this->anonClient->getResponse()->isRedirect($this->p($this->u('/' . $this->path . '/', true))));

        // first controller without slash should add slash to the end of the url, even if not logged in
        $this->anonClient->request('GET', $this->u('/' . $this->path, true));
        $this->assertEquals(301, $this->anonClient->getResponse()->getStatusCode());
        $this->assertTrue($this->anonClient->getResponse()->isRedirect($this->p($this->u('/' . $this->path . '/', true))));
    }

    public function testIndexStatusCode()
    {
        // controller index should return 200
        $this->anonClient->request('GET', $this->u('/' . $this->path . '/'));
        $this->assertEquals(200, $this->anonClient->getResponse()->getStatusCode());

        // controller index should return 401, when we are not logged in
        $this->anonClient->request('GET', $this->u('/' . $this->path . '/', true));
        $this->assertEquals(403, $this->anonClient->getResponse()->getStatusCode());

        // controller index should return 200, when we are logged and authorized
        $this->adminClient->request('GET', $this->u('/' . $this->path . '/', true));
        $this->assertEquals(200, $this->adminClient->getResponse()->getStatusCode());
    }

    public function testIndexResponseAction()
    {

        // test index response
        $this->adminClient->request('GET', $this->u('/' . $this->path . '/', true));
        $this->assertEquals(200, $this->adminClient->getResponse()->getStatusCode());

        $data = json_decode($this->adminClient->getResponse()->getContent(), true);
        $this->assertArrayHasKey('app', $data);
        $this->assertArrayHasKey('layout', $data);
        $this->assertEquals($data['layout'], 'layout.html.twig');

        // test index embed response
        $this->adminClient->request('GET', $this->u('/' . $this->path . '/?embed=true', true));
        $this->assertEquals(200, $this->adminClient->getResponse()->getStatusCode());
        $data = json_decode($this->adminClient->getResponse()->getContent(), true);
        $this->assertEquals($data['layout'], 'layout-embed.html.twig');
    }
}
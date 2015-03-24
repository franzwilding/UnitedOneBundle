<?php

namespace United\OneBundle\Tests\Functional;

class CardControllerTest extends FunctionalControllerTestCase
{

    private $path = 'cardcontrollertest';


    public function testRedirectAction()
    {
        // first controller without slash should add slash to the end of the url
        $this->anonClient->request('GET', $this->u('/' . $this->path));
        $this->assertEquals(301, $this->anonClient->getResponse()->getStatusCode());
        $this->assertTrue($this->anonClient->getResponse()->isRedirect($this->p($this->u('/' . $this->path . '/'))));

        // base route redirect action should add a slash to the end of the url, even if not logged in
        $this->anonClient->request('GET', $this->u('', true));
        $this->assertEquals(301, $this->anonClient->getResponse()->getStatusCode());
        $this->assertTrue($this->anonClient->getResponse()->isRedirect($this->p($this->u('/', true))));

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

        // controller index should return 200, when we are logged and authorized. We use embed, we don't need the full HTML page.
        $this->adminClient->request('GET', $this->u('/' . $this->path . '/?embed=true', true));
        $this->assertEquals(200, $this->adminClient->getResponse()->getStatusCode());
    }

    public function testIndexResponseAction()
    {

        // test index response
        $this->adminClient->request('GET', $this->u('/' . $this->path . '//?embed=true', true));
        $this->assertEquals(200, $this->adminClient->getResponse()->getStatusCode());

        // TODO: Check content
    }

    public function testCreateStatusCode()
    {
        // TODO
    }

    public function testCreateResponseAction()
    {
        // TODO
    }

    public function testUpdateStatusCode()
    {
        // TODO
    }

    public function testUpdateResponseAction()
    {
        // TODO
    }

    public function testDeleteStatusCode()
    {
        // TODO
    }

    public function testDeleteResponseAction()
    {
        // TODO
    }
}
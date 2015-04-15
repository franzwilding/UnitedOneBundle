<?php

namespace United\OneBundle\Tests\Functional;

class SectionControllerTest extends FunctionalControllerTestCase
{

    private $path = 'sectioncontrollertest';

    private function checkStatusCodes($path)
    {
        // controller index should return 200
        $this->anonClient->request('GET', $this->u('/'.$this->path.$path));
        $this->assertEquals(
          200,
          $this->anonClient->getResponse()->getStatusCode()
        );

        // controller index should return 401, when we are not logged in
        $this->anonClient->request(
          'GET',
          $this->u('/'.$this->path.$path, true)
        );
        $this->assertEquals(
          403,
          $this->anonClient->getResponse()->getStatusCode()
        );

        // controller index should return 200, when we are logged and authorized.
        $this->adminClient->request(
          'GET',
          $this->u('/'.$this->path.$path, true)
        );
        $this->assertEquals(
          200,
          $this->adminClient->getResponse()->getStatusCode()
        );
    }


    public function testRedirectAction()
    {
        // first controller without slash should add slash to the end of the url
        $this->anonClient->request('GET', $this->u('/'.$this->path));
        $this->assertEquals(
          301,
          $this->anonClient->getResponse()->getStatusCode()
        );
        $this->assertTrue(
          $this->anonClient->getResponse()->isRedirect(
            $this->p($this->u('/'.$this->path.'/'))
          )
        );

        // base route redirect action should add a slash to the end of the url, even if not logged in
        $this->anonClient->request('GET', $this->u('', true));
        $this->assertEquals(
          301,
          $this->anonClient->getResponse()->getStatusCode()
        );
        $this->assertTrue(
          $this->anonClient->getResponse()->isRedirect(
            $this->p($this->u('/', true))
          )
        );

        // first controller without slash should add slash to the end of the url, even if not logged in
        $this->anonClient->request('GET', $this->u('/'.$this->path, true));
        $this->assertEquals(
          301,
          $this->anonClient->getResponse()->getStatusCode()
        );
        $this->assertTrue(
          $this->anonClient->getResponse()->isRedirect(
            $this->p($this->u('/'.$this->path.'/', true))
          )
        );
    }

    public function testIndexStatusCode()
    {
        $this->checkStatusCodes('/');
    }

    public function testSubSectionsStatusCode()
    {
        $this->checkStatusCodes('/sub1/');
        $this->checkStatusCodes('/sub2/');
        $this->checkStatusCodes('/sub1/sub1/');
    }

    public function testActions()
    {
        // test index response
        $crawler1 = $this->adminClient->request(
          'GET',
          $this->u('/'.$this->path.'/', true)
        );
        $this->assertEquals(
          200,
          $this->adminClient->getResponse()->getStatusCode()
        );

        // index should two cards for the two subroutes
        $this->assertEquals(2, $crawler1->filter('a.card.fluid')->count());

        // click on first subsection
        $crawler = $this->adminClient->click(
          $crawler1->filter('a.card.fluid')->first()->link()
        );
        $this->assertEquals(
          200,
          $this->adminClient->getResponse()->getStatusCode()
        );

        // we should now see the sub sub section 1
        $this->assertEquals(1, $crawler->filter('a.card.fluid')->count());

        // click on the sub sub section
        $this->adminClient->click(
          $crawler->filter('a.card.fluid')->first()->link()
        );
        $this->assertEquals(
          200,
          $this->adminClient->getResponse()->getStatusCode()
        );


        // click on last subsection
        $this->adminClient->click(
          $crawler1->filter('a.card.fluid')->last()->link()
        );
        $this->assertEquals(
          200,
          $this->adminClient->getResponse()->getStatusCode()
        );
    }
}
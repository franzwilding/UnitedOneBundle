<?php

namespace United\OneBundle\Tests\Functional;

class CardControllerTest extends FunctionalControllerTestCase
{

    private $path = 'cardcontrollertest';


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
        // controller index should return 200
        $this->anonClient->request('GET', $this->u('/'.$this->path.'/'));
        $this->assertEquals(
          200,
          $this->anonClient->getResponse()->getStatusCode()
        );

        // controller index should return 401, when we are not logged in
        $this->anonClient->request('GET', $this->u('/'.$this->path.'/', true));
        $this->assertEquals(
          403,
          $this->anonClient->getResponse()->getStatusCode()
        );

        // controller index should return 200, when we are logged and authorized. We use embed, we don't need the full HTML page.
        $this->adminClient->request('GET', $this->u('/'.$this->path.'/', true));
        $this->assertEquals(
          200,
          $this->adminClient->getResponse()->getStatusCode()
        );
    }

    public function testCRUDActions()
    {

        // test index response
        $crawler = $this->adminClient->request(
          'GET',
          $this->u('/'.$this->path.'/', true)
        );
        $this->assertEquals(
          200,
          $this->adminClient->getResponse()->getStatusCode()
        );

        // Check that there is an add button on the page
        $this->assertGreaterThan(
          0,
          $crawler->filter('a.single.button.card')->count()
        );

        // Click on the create link
        $crawler = $this->adminClient->click(
          $crawler->filter('a.single.button.card')->first()->link()
        );
        $this->assertEquals(
          200,
          $this->adminClient->getResponse()->getStatusCode()
        );

        // Check that the form gets rendered
        $this->assertGreaterThan(0, $crawler->filter('form')->count());
        $form = $crawler->filter('form')->first();

        $this->assertGreaterThan(
          0,
          $form->filter('input[name="form[title]"]')->count()
        );
        $this->assertGreaterThan(
          0,
          $form->filter('input[name="form[_token]"]')->count()
        );

        // Fill out form
        $this->adminClient->submit(
          $form->form(),
          array('form[title]' => 'New Entity')
        );

        // Should be redirecting to index
        $this->assertEquals(
          302,
          $this->adminClient->getResponse()->getStatusCode()
        );
        $this->assertTrue(
          $this->adminClient->getResponse()->isRedirect(
            $this->u('/'.$this->path.'/', true)
          )
        );
        $crawler = $this->adminClient->followRedirect();
        $this->assertEquals(
          200,
          $this->adminClient->getResponse()->getStatusCode()
        );

        // Now we should see an add button and a card item for our new entity
        $this->assertGreaterThan(
          0,
          $crawler->filter('a.single.button.card')->count()
        );
        $this->assertGreaterThan(
          0,
          $crawler->filter('a:contains("New Entity")')->count()
        );

        // Update the entity
        $crawler = $this->adminClient->click(
          $crawler->filter('a:contains("New Entity")')->first()->link()
        );
        $this->assertEquals(
          200,
          $this->adminClient->getResponse()->getStatusCode()
        );

        // Check that the form gets rendered
        $this->assertGreaterThan(0, $crawler->filter('form')->count());
        $form = $crawler->filter('form')->first();

        $this->assertGreaterThan(
          0,
          $form->filter('input[name="form[title]"]')->count()
        );
        $this->assertGreaterThan(
          0,
          $form->filter('input[name="form[_token]"]')->count()
        );

        // We should also see an cancel button
        $this->assertGreaterThan(
          0,
          $form->filter('a:contains("Cancel")')->count()
        );

        // Hit the cancel button
        $crawler = $this->adminClient->click(
          $crawler->filter('a:contains("Cancel")')->first()->link()
        );
        $this->assertEquals(
          200,
          $this->adminClient->getResponse()->getStatusCode()
        );

        // We should now be on the index page again
        $crawler = $this->adminClient->click(
          $crawler->filter('a:contains("New Entity")')->first()->link()
        );
        $this->assertEquals(
          200,
          $this->adminClient->getResponse()->getStatusCode()
        );
        $form = $crawler->filter('form')->first();

        // Fill out form
        $this->adminClient->submit(
          $form->form(),
          array('form[title]' => 'Updated Entity')
        );

        // Should be redirecting to index
        $this->assertEquals(
          302,
          $this->adminClient->getResponse()->getStatusCode()
        );
        $this->assertTrue(
          $this->adminClient->getResponse()->isRedirect(
            $this->u('/'.$this->path.'/', true)
          )
        );
        $crawler = $this->adminClient->followRedirect();
        $this->assertEquals(
          200,
          $this->adminClient->getResponse()->getStatusCode()
        );

        // Now we should see an add button and a card item for our updated entity
        $this->assertGreaterThan(
          0,
          $crawler->filter('a.single.button.card')->count()
        );
        $this->assertGreaterThan(
          0,
          $crawler->filter('a:contains("Updated Entity")')->count()
        );

        // Click on the Update Entity link to get to the delete button
        $crawler = $this->adminClient->click(
          $crawler->filter('a:contains("Updated Entity")')->first()->link()
        );
        $this->assertEquals(
          200,
          $this->adminClient->getResponse()->getStatusCode()
        );

        $this->assertGreaterThan(
          0,
          $crawler->filter('a:contains("Delete")')->count()
        );
        $crawler = $this->adminClient->click(
          $crawler->filter('a:contains("Delete")')->first()->link()
        );

        // Now we should see an delete confirmation screen
        $this->assertGreaterThan(
          0,
          $crawler->filter('button:contains("Delete")')->count()
        );
        $this->assertGreaterThan(
          0,
          $crawler->filter('a:contains("Cancel")')->count()
        );

        // Test clicking on cancel
        $crawler = $this->adminClient->click(
          $crawler->filter('a:contains("Cancel")')->first()->link()
        );
        $this->assertEquals(
          200,
          $this->adminClient->getResponse()->getStatusCode()
        );

        // Click on the edit button
        $crawler = $this->adminClient->click(
          $crawler->filter('a:contains("Updated Entity")')->first()->link()
        );

        // Click on the delete button
        $crawler = $this->adminClient->click(
          $crawler->filter('a:contains("Delete")')->first()->link()
        );

        // Now fill out the delete form
        $form = $crawler->filter('form')->first();
        $this->adminClient->submit($form->form());

        // Should be redirecting to index
        $this->assertEquals(
          302,
          $this->adminClient->getResponse()->getStatusCode()
        );
        $this->assertTrue(
          $this->adminClient->getResponse()->isRedirect(
            $this->u('/'.$this->path.'/', true)
          )
        );
        $crawler = $this->adminClient->followRedirect();
        $this->assertEquals(
          200,
          $this->adminClient->getResponse()->getStatusCode()
        );

    }
}
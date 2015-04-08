<?php

namespace United\OneBundle\Tests\Functional;

class CollectionControllerTest extends FunctionalControllerTestCase
{

    private $path = 'collectioncontrollertest';


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
        $this->adminClient->request('GET', $this->u('/' . $this->path . '/', true));
        $this->assertEquals(200, $this->adminClient->getResponse()->getStatusCode());
    }

    public function testCRUDActions()
    {

        // when there are no collection entities, the index action should render a message and a create button
        $crawler = $this->adminClient->request('GET', $this->u('/' . $this->path . '/', true));
        $this->assertEquals(200, $this->adminClient->getResponse()->getStatusCode());

        $this->assertEquals(1, $crawler->filter('a.positive.button:contains("Create the first collection")')->count());

        // Click on the Update Entity link to get to the delete button
        $crawler = $this->adminClient->click($crawler->filter('a.positive.button:contains("Create the first collection")')->first()->link());

        // Check that the form gets rendered
        $this->assertGreaterThan(0, $crawler->filter('form')->count());
        $form = $crawler->filter('form')->first();

        $this->assertGreaterThan(0, $form->filter('input[name="form[title]"]')->count());
        $this->assertGreaterThan(0, $form->filter('input[name="form[_token]"]')->count());

        // Fill out form
        $this->adminClient->submit($form->form(), array('form[title]' => 'First Collection Entity'));

        // Should redirect to index
        $this->assertEquals(302, $this->adminClient->getResponse()->getStatusCode());
        $this->assertTrue($this->adminClient->getResponse()->isRedirect($this->u('/' . $this->path . '/', true)));
        $this->adminClient->followRedirect();


        // Should redirect to first entity
        $this->assertEquals(302, $this->adminClient->getResponse()->getStatusCode());
        $this->assertTrue($this->adminClient->getResponse()->isRedirect($this->u('/' . $this->path . '/1', true)));
        $crawler = $this->adminClient->followRedirect();

        // Should render first collection entity with any child controllers
        $this->assertEquals(200, $this->adminClient->getResponse()->getStatusCode());

        // There should be an menu of collection items
        $this->assertEquals(1, $crawler->filter('div.ui.menu')->count());
        $menu = $crawler->filter('div.ui.menu')->first();

        // The menu should contain the first entity and a create button
        $this->assertEquals(1, $menu->filter('a.active.item:contains("First Collection Entity")')->count());
        $this->assertEquals(1, $menu->filter('a.item:contains("Add collection")')->count());

        // And there should be the first collection item with edit button and it's child controllers
        $this->assertEquals(1, $crawler->filter('h2:contains("First Collection Entity")')->count());
        $this->assertGreaterThan(0, $crawler->filter('a.button:contains("Edit")')->count());

        // click on collection edit
        $crawler = $this->adminClient->click($crawler->filter('a.button:contains("Edit")')->first()->link());

        // Fill out the edit form
        $form = $crawler->filter('form')->first();
        $this->adminClient->submit($form->form(), array('form[title]' => 'Updated Collection Entity'));
        $this->assertEquals(302, $this->adminClient->getResponse()->getStatusCode());
        $this->assertTrue($this->adminClient->getResponse()->isRedirect($this->u('/' . $this->path . '/', true)));
        $this->adminClient->followRedirect();
        $this->assertEquals(302, $this->adminClient->getResponse()->getStatusCode());
        $this->assertTrue($this->adminClient->getResponse()->isRedirect($this->u('/' . $this->path . '/1', true)));
        $crawler = $this->adminClient->followRedirect();
        $this->assertEquals(1, $crawler->filter('h2:contains("Updated Collection Entity")')->count());


        // click on add new collection
        $crawler = $this->adminClient->click($crawler->filter('a.item:contains("Add collection")')->first()->link());
        $form = $crawler->filter('form')->first();
        $this->adminClient->submit($form->form(), array('form[title]' => '2nd Collection Entity'));
        $this->assertEquals(302, $this->adminClient->getResponse()->getStatusCode());
        $this->assertTrue($this->adminClient->getResponse()->isRedirect($this->u('/' . $this->path . '/', true)));
        $this->adminClient->followRedirect();
        $this->assertEquals(302, $this->adminClient->getResponse()->getStatusCode());
        $this->assertTrue($this->adminClient->getResponse()->isRedirect($this->u('/' . $this->path . '/1', true)));

        $crawler = $this->adminClient->followRedirect();
        $menu = $crawler->filter('div.ui.menu')->first();
        $this->assertEquals(1, $crawler->filter('h2:contains("Updated Collection Entity")')->count());
        $this->assertEquals(1, $menu->filter('a.active.item:contains("Updated Collection Entity")')->count());
        $this->assertEquals(1, $menu->filter('a.item:contains("2nd Collection Entity")')->count());

        // navigate to the new collection
        $crawler = $this->adminClient->click($crawler->filter('a.item:contains("2nd Collection Entity")')->first()->link());
        $menu = $crawler->filter('div.ui.menu')->first();
        $this->assertEquals(1, $crawler->filter('h2:contains("2nd Collection Entity")')->count());
        $this->assertEquals(1, $menu->filter('a.item:contains("Updated Collection Entity")')->count());
        $this->assertEquals(1, $menu->filter('a.active.item:contains("2nd Collection Entity")')->count());

        // the view action should render all undited structure children of the collection item.

        // there should be a table controller output
        $this->assertEquals(1, $crawler->filter('table.ui.table')->count());
        $table = $crawler->filter('table.ui.table')->first();

        // add a new entity using the table controller
        $crawler = $this->adminClient->click($table->filter('a.positive.button:contains("Add")')->first()->link());
        $form = $crawler->filter('form')->first();
        $this->adminClient->submit($form->form(), array('form[title]' => 'New Entity with table controller'));
        $this->assertEquals(302, $this->adminClient->getResponse()->getStatusCode());

        // for now, the child controller just redirects to it's index action.
        $this->assertTrue($this->adminClient->getResponse()->isRedirect($this->u('/' . $this->path . '/c1/', true)));
        $this->adminClient->followRedirect();
        $this->assertEquals(200, $this->adminClient->getResponse()->getStatusCode());
    }
}
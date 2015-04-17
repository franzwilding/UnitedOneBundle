<?php

namespace United\OneBundle\Tests\Functional;

class TagsControllerTest extends FunctionalControllerTestCase
{

    private $path = 'tagscontrollertest';

    public function testTagsForEmptyEntityEmptyCollection()
    {
        $form = $this->getCreateForm();

        // Form should contain an empty search select box.
        $this->assertEquals(1, $form->filter('select.search')->count());

        $form = $form->form();
        $values = $form->getPhpValues();
        $values['form']['title'] = 'New Tag Collection';

        // Simulating tag creation (js)
        $values['form']['children'] = array(
            0 => array('name' => '1. Tag'),
            1 => array('name' => '2. Tag'),
            2 => array('name' => '3. Tag'),
            3 => array('name' => '4. Tag'),
        );

        $this->adminClient->request($form->getMethod(), $form->getUri(), $values, $form->getPhpFiles());

        // Check, if tags got created successfully
        $this->assertEquals(302, $this->adminClient->getResponse()->getStatusCode());

        $tags = $this->em->getRepository('UnitedOneBundle:TagsMock')->findAll();
        $this->assertCount(1, $tags);
        $tag = $tags[0];
        $this->assertEquals('New Tag Collection', $tag->getTitle());
        $this->assertCount(4, $tag->getChildren());

        // Now let's try to remove some of the tags
        $form = $this->getUpdateForm($tag->getId());

        // Form should contain an empty search select box.
        $this->assertEquals(1, $form->filter('select.search')->count());

        // Search should have the 4 created children as options
        $search = $form->filter('select.search')->first();
        $this->assertEquals(5, $search->children()->count());
        foreach($tag->getChildren() as $key => $child) {
            $this->assertEquals($key, $search->children()->getNode($key+1)->getAttribute('value'));
            $this->assertEquals($child->getTitle(), $search->children()->getNode($key+1)->textContent);
        }


        $form = $form->form();
        $values = $form->getPhpValues();

        // The form should have hidden inputs for all added children
        foreach($tag->getChildren() as $key => $child) {
            $this->assertEquals($child->getTitle(), $values['form']['children'][$key]['name']);
        }

        // remove the last two tags
        $child2 = $values['form']['children'][2];
        $child3 = $values['form']['children'][3];
        unset($values['form']['children'][2]);
        unset($values['form']['children'][3]);
        $this->adminClient->request($form->getMethod(), $form->getUri(), $values, $form->getPhpFiles());

        // Check, if tags got updated successfully
        $this->assertEquals(302, $this->adminClient->getResponse()->getStatusCode());

        // the tag should now have only two children
        $this->em->refresh($tag);
        $this->assertCount(2, $tag->getChildren());
        foreach($tag->getChildren() as $key => $child) {
            $this->assertEquals($values['form']['children'][$key]['name'], $child->getTitle());
        }


        // Add one child again
        $form = $this->getUpdateForm($tag->getId());

        // Form should contain an empty search select box.
        $this->assertEquals(1, $form->filter('select.search')->count());

        // Search should have the 4 created children as options
        $search = $form->filter('select.search')->first();
        $this->assertEquals(5, $search->children()->count());

        // Now add child with id 2 again
        $form = $form->form();
        $values = $form->getPhpValues();
        $values['form']['children'][2] = $child2;
        $values['form']['children'][2]['id'] = 2;
        $this->adminClient->request($form->getMethod(), $form->getUri(), $values, $form->getPhpFiles());

        // Check, if tags got updated successfully
        $this->assertEquals(302, $this->adminClient->getResponse()->getStatusCode());

        // Check if child tag got added
        $this->em->refresh($tag);
        $this->assertCount(3, $tag->getChildren());
        foreach($tag->getChildren() as $key => $child) {
            $this->assertEquals($values['form']['children'][$key]['name'], $child->getTitle());
        }

        // Check if child tag was not created again, because we added it as reference
        $children = $this->em->getRepository('UnitedOneBundle:TagMock')->findAll();
        $this->assertCount(4, $children);


        // Now let's update the tag again and add one reference item and one new and delete one existing
        $form = $this->getUpdateForm($tag->getId());

        // Form should contain an empty search select box.
        $this->assertEquals(1, $form->filter('select.search')->count());

        // Search should have the 4 created children as options
        $search = $form->filter('select.search')->first();
        $this->assertEquals(5, $search->children()->count());

        // Now add child with id 2 again
        $form = $form->form();
        $values = $form->getPhpValues();
        unset($values['form']['children'][0]);
        $values['form']['children'][3] = $child3;
        $values['form']['children'][3]['id'] = 3;
        $values['form']['children'][4] = array( 'name' => '5. Tag' );
        $this->adminClient->request($form->getMethod(), $form->getUri(), $values, $form->getPhpFiles());

        // Check, if tags got updated successfully
        $this->assertEquals(302, $this->adminClient->getResponse()->getStatusCode());

        // Check children
        $this->em->refresh($tag);
        $this->assertCount(4, $tag->getChildren());
        $child_names = array('2. Tag', '3. Tag', '4. Tag', '5. Tag');
        foreach($tag->getChildren() as $key => $child) {
            $this->assertEquals($child_names[$key], $child->getTitle());
        }

        // Check if the new tag was created
        $children = $this->em->getRepository('UnitedOneBundle:TagMock')->findAll();
        $this->assertCount(5, $children);
        $this->assertEquals('5. Tag', $children[4]->getTitle());

    }


    private function getCreateForm()
    {
        $crawler = $this->adminClient->request('GET', $this->u('/'.$this->path.'/create', true));

        // Check that the form gets rendered
        $this->assertGreaterThan(0, $crawler->filter('form')->count());
        return $crawler->filter('form')->first();
    }

    private function getUpdateForm($id)
    {
        $crawler = $this->adminClient->request('GET', $this->u('/'.$this->path.'/' . $id . '/update', true));

        // Check that the form gets rendered
        $this->assertGreaterThan(0, $crawler->filter('form')->count());
        return $crawler->filter('form')->first();
    }
}
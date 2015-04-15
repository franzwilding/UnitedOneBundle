<?php

namespace United\OneBundle\Tests\Functional;

class TagsControllerTest extends FunctionalControllerTestCase
{

    private $path = 'tagscontrollertest';

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


    public function testTagsForEmptyEntityEmptyCollection()
    {
        $form = $this->getCreateForm();
        print_r($this->adminClient->getResponse()->getContent());
    }

    public function testTagsForEmptyEntity()
    {
    }

    public function testTagsForEmptyCollection()
    {
    }

    public function testTagsAddExisting()
    {
    }

    public function testTagsAddNew()
    {
    }

    public function testTagsDeleteExisting()
    {
    }

    public function testTagsAddMultiple()
    {
    }
}
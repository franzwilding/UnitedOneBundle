<?php

namespace United\OneBundle\Tests\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Form\Exception;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormConfigBuilder;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use United\CoreBundle\Tests\Mock\EntityManagerMock;
use United\OneBundle\EventListener\ExistingTagsEventListener;
use Symfony\Component\Form\Test\TypeTestCase;
use United\OneBundle\Form\Type\DeleteType;
use United\OneBundle\Tests\tests\Entities\TagMock;

class ExistingTagsEventListenerTest extends TypeTestCase
{

    public function testProcessingExistingTags()
    {

        // holds post vars, so ExistingTagsEventListener can alter $collection
        $post_data = array(
          0 => array('id' => 0, 'name' => 'Existing Tag 0'),
          1 => array('id' => 1, 'name' => 'Existing Tag 1'),
          2 => array('name' => 'New Tag 2'),
          3 => array('name' => 'New Tag 3'),
          4 => array('name' => 'New Tag 4'),
          5 => array('id' => 5, 'name' => 'Existing Tag 5'),
        );

        // holds the real tag entities
        $collection = new ArrayCollection($post_data);

        // must hold all replacement tags
        $options = array(
          0 => array('id' => 0, 'name' => 'Replaced Tag 0'),
          1 => array('id' => 1, 'name' => 'Replaced Tag 1'),
          5 => array('id' => 5, 'name' => 'Replaced Tag 5'),
        );

        $form = $this->factory->create(new DeleteType());

        $listener = new ExistingTagsEventListener($options);

        $event = new FormEvent($form, $post_data);
        $listener->preSubmit($event);

        $post_data = array();
        $post_data[0] = new TagMock();
        $post_data[0]->setId(0);
        $post_data[0]->setName('Existing Tag 0');
        $post_data[1] = new TagMock();
        $post_data[1]->setId(1);
        $post_data[1]->setName('Existing Tag 1');
        $post_data[2] = new TagMock();
        $post_data[2]->setName('New Tag 2');
        $post_data[3] = new TagMock();
        $post_data[3]->setName('New Tag 3');
        $post_data[4] = new TagMock();
        $post_data[4]->setName('New Tag 4');
        $post_data[5] = new TagMock();
        $post_data[5]->setId(5);
        $post_data[5]->setName('Existing Tag 5');
        $collection = new ArrayCollection($post_data);

        $event = new FormEvent($form, $collection);
        $listener->postSubmit($event);

        // The final collection should have the elements with id 0, 4 and 8 replaced
        $this->assertCount(6, $collection);
        $this->assertTrue($collection->contains($post_data[2]));
        $this->assertTrue($collection->contains($post_data[3]));
        $this->assertTrue($collection->contains($post_data[4]));
        $this->assertTrue(
          $collection->contains(array('id' => 0, 'name' => 'Replaced Tag 0'))
        );
        $this->assertTrue(
          $collection->contains(array('id' => 1, 'name' => 'Replaced Tag 1'))
        );
        $this->assertTrue(
          $collection->contains(array('id' => 5, 'name' => 'Replaced Tag 5'))
        );
    }

}
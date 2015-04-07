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

class ExistingTagsEventListenerTest extends TypeTestCase
{

    public function testProcessingExistingTags()
    {

        // holds the real tag entities
        $collection = new ArrayCollection();

        // holds post vars, so ExistingTagsEventListener can alter $collection
        $options = array();

        $form = $this->factory->create(new DeleteType());
        $event = new FormEvent($form, $collection);
        $listener = new ExistingTagsEventListener($options);
        $listener->preSubmit($event);
        $listener->postSubmit($event);

        //TODO: Check, that ExistingTagsEventListener will replace existing tags from collection with existing ones instead of creating them again,
    }

}
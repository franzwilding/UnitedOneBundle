<?php

namespace United\OneBundle\Tests\Form\Type;

use appTestDebugProjectContainer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use United\OneBundle\Form\Type\DeleteType;

class DeleteTypeTest extends KernelTestCase
{

    /**
     * @var appTestDebugProjectContainer $container
     */
    private $container;

    public function testCreateSubmitOrDeleteButtonInstances()
    {
        $class = 'Symfony\Component\Form\SubmitButton';
        $form = $this->container->get('form.factory')->create('delete');
        $this->assertInstanceOf($class, $form);

        $this->assertEquals('delete', $form->getName());
    }

    public function testSubmitOrDeleteCanBeAddedToForm()
    {
        $form = $this->container->get('form.factory')
          ->createBuilder('form')
          ->getForm();

        $this->assertSame($form, $form->add('send', 'delete'));
    }

    public function testFormType()
    {

        $type = new DeleteType();
        $form = $this->container->get('form.factory')->create($type);
        $this->assertTrue($form->isSynchronized());

    }

    protected function setUp()
    {
        self::bootKernel();
        $this->container = static::$kernel->getContainer();
    }
}
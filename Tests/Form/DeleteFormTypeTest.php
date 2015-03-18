<?php

namespace United\OneBundle\Tests\Form\Type;

use appTestDebugProjectContainer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use United\OneBundle\Form\DeleteFormType;

class DeleteFormTypeTest extends KernelTestCase
{

    /**
     * @var appTestDebugProjectContainer $container
     */
    private $container;

    public function testCreateSubmitOrDeleteButtonInstances()
    {
        $class = 'Symfony\Component\Form\Form';
        $form = $this->container->get('form.factory')->create(new DeleteFormType());
        $this->assertInstanceOf($class, $form);

        $this->assertEquals('united_one_delete_form', $form->getName());
    }

    public function testFormType()
    {

        $obj = new \stdClass();
        $obj->title = "testFormType";

        $type = new DeleteFormType();
        $form = $this->container->get('form.factory')->create($type, $obj);
        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($obj, $form->getData());

    }

    protected function setUp()
    {
        self::bootKernel();
        $this->container = static::$kernel->getContainer();
    }
}
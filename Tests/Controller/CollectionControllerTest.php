<?php

namespace United\OneBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use United\OneBundle\Tests\Mock\CollectionControllerMock;

class CollectionControllerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var CollectionControllerMock $controller
     */
    private $controller;

    /**
     * @var ContainerBuilder $container
     */
    private $container;

    public function setUp()
    {
        $this->controller = new CollectionControllerMock();
        $this->container = new ContainerBuilder();
        $this->controller->setContainer($this->container);
        $this->container->set('request', new Request());
        parent::setUp();
    }

    /**
     * Check, that the right templates get returned by getTemplateForAction.
     */
    public function testTemplates()
    {
        $this->assertEquals('UnitedOneBundle:Collection:index.html.twig', $this->controller->getM('getTemplateForAction', array('index')));
        $this->assertEquals('UnitedOneBundle:Collection:view.html.twig', $this->controller->getM('getTemplateForAction', array('view')));
        $this->assertEquals('UnitedOneBundle:Collection:item.html.twig', $this->controller->getM('getTemplateForAction', array('item')));
        $this->assertEquals('UnitedOneBundle:Form:form.html.twig', $this->controller->getM('getTemplateForAction', array('create')));
        $this->assertEquals('UnitedOneBundle:Form:form.html.twig', $this->controller->getM('getTemplateForAction', array('update')));
        $this->assertEquals('UnitedOneBundle:Form:form.html.twig', $this->controller->getM('getTemplateForAction', array('delete')));
    }

    /**
     * Checks, that on view, the item Template get passed as context variable.
     */
    public function testContext()
    {
        $context = array();
        $this->controller->getM('alterContextForAction', array('view', &$context));
        $this->assertArrayHasKey('itemTemplate', $context);
        $this->assertEquals('UnitedOneBundle:Collection:item.html.twig', $context['itemTemplate']);
    }
}
<?php

namespace United\OneBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use United\OneBundle\Tests\Mock\CardControllerMock;

class CardControllerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var CardControllerMock $controller
     */
    private $controller;

    /**
     * @var ContainerBuilder $container
     */
    private $container;

    public function setUp()
    {
        $this->controller = new CardControllerMock();
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
        $this->assertEquals('UnitedOneBundle:Card:index.html.twig', $this->controller->getM('getTemplateForAction', array('index')));
        $this->assertEquals('UnitedOneBundle:Card:card.html.twig', $this->controller->getM('getTemplateForAction', array('card')));
        $this->assertEquals('UnitedOneBundle:Form:form.html.twig', $this->controller->getM('getTemplateForAction', array('create')));
        $this->assertEquals('UnitedOneBundle:Form:form.html.twig', $this->controller->getM('getTemplateForAction', array('update')));
        $this->assertEquals('UnitedOneBundle:Form:form.html.twig', $this->controller->getM('getTemplateForAction', array('delete')));
    }

    /**
     * Checks, that on index, the cardTemplate get passed as context variable.
     */
    public function testContext()
    {
        $context = array();
        $this->controller->getM('alterContextForAction', array('index', &$context));
        $this->assertArrayHasKey('cardTemplate', $context);
        $this->assertEquals('UnitedOneBundle:Card:card.html.twig', $context['cardTemplate']);
    }
}
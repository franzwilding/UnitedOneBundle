<?php

namespace United\OneBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use United\OneBundle\Tests\Mock\TableControllerMock;

class TableControllerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var TableControllerMock $controller
     */
    private $controller;

    /**
     * @var ContainerBuilder $container
     */
    private $container;

    public function setUp()
    {
        $this->controller = new TableControllerMock();
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
        $this->assertEquals(
          'UnitedOneBundle:Table:index.html.twig',
          $this->controller->getM('getTemplateForAction', array('index'))
        );
        $this->assertEquals(
          'UnitedOneBundle:Table:row.html.twig',
          $this->controller->getM('getTemplateForAction', array('row'))
        );
        $this->assertEquals(
          'UnitedOneBundle:Form:form.html.twig',
          $this->controller->getM('getTemplateForAction', array('create'))
        );
        $this->assertEquals(
          'UnitedOneBundle:Form:form.html.twig',
          $this->controller->getM('getTemplateForAction', array('update'))
        );
        $this->assertEquals(
          'UnitedOneBundle:Form:form.html.twig',
          $this->controller->getM('getTemplateForAction', array('delete'))
        );
    }

    /**
     * Checks, that on index, the sectionTemplate get passed as context variable.
     */
    public function testContext()
    {
        $context = array();
        $this->controller->getM(
          'alterContextForAction',
          array('index', &$context)
        );
        $this->assertArrayHasKey('rowTemplate', $context);
        $this->assertEquals(
          'UnitedOneBundle:Table:row.html.twig',
          $context['rowTemplate']
        );
    }
}
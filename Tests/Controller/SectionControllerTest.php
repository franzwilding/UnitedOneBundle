<?php

namespace United\OneBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use United\OneBundle\Tests\Mock\SectionControllerMock;

class SectionControllerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var SectionControllerMock $controller
     */
    private $controller;

    /**
     * @var ContainerBuilder $container
     */
    private $container;

    public function setUp()
    {
        $this->controller = new SectionControllerMock();
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
          'UnitedOneBundle:Section:index.html.twig',
          $this->controller->getM('getTemplateForAction', array('index'))
        );
        $this->assertEquals(
          'UnitedOneBundle:Section:section.html.twig',
          $this->controller->getM('getTemplateForAction', array('section'))
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
        $this->assertArrayHasKey('sectionTemplate', $context);
        $this->assertEquals(
          'UnitedOneBundle:Section:section.html.twig',
          $context['sectionTemplate']
        );
    }
}
<?php

namespace United\OneBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Request;
use United\CoreBundle\Tests\Controller\UnitedControllerTestCase;
use United\OneBundle\Tests\Mock\PlainBaseControllerMock;

class PlainBaseControllerTest extends UnitedControllerTestCase
{

    protected function setUp()
    {
        $this->controller = new PlainBaseControllerMock();
        parent::setUp();
    }

    /**
     * Calling indexAction on PlainController should render the template, set
     * by getTemplateForAction('index').
     */
    public function testIndexAction()
    {

        // Test sending emptyrequest
        $request = new Request();
        $this->container->enterScope('request');
        $this->container->set('request', $request, 'request');

        // Check index template rendering: no embed layout
        $context = json_decode($this->getActionContent('index'), true);
        $this->assertArrayHasKey('layout', $context);
        $this->assertEquals('layout.html.twig', $context['layout']);

        // Check embed layout
        $request->query->add(array('embed' => true));
        $context = json_decode($this->getActionContent('index'), true);
        $this->assertEquals('layout-embed.html.twig', $context['layout']);

        // Check controller routes
        $this->checkControllerRoutes(array(

            // index route
            $this->getClassPrefix() . '.united_one_tests_mock_plainbasecontrollermock_index' => array(
                'path' => '/United_OneBundle_Tests_Controller_PlainBaseControllerTest/',
                'defaults' => array(
                    '_controller' => 'United\OneBundle\Tests\Mock\PlainBaseControllerMock::indexAction',
                ),
            ),

            // root redirect route
            'united.' . $this->getClassPrefix() => array(
                'path' => '/',
                'defaults' => array(
                    '_controller' => 'Symfony\Bundle\FrameworkBundle\Controller\RedirectController::redirectAction',
                    'route' => $this->getClassPrefix() . '.united_one_tests_mock_plainbasecontrollermock_index',
                    'permanent' => true,
                ),
            ),
        ));
    }

    /**
     * Check that access on index action is checked by the controller, if we set the united config secure flag true.
     */
    public function testIndexActionAccess()
    {

        // Enable security
        $this->enableStructureSecurity();

        // Inject an structure item
        $id = $this->injectStructureItem();

        // Accessing index without granting should throw an access denied exception.
        $this->setAuthCheckerGrant(array());
        $this->checkAccessDeniedException('index', true);
        $this->checkAuthChecker(0, $id, 'access');

        // When we grant the access, we should not get any exceptions
        $this->setAuthCheckerGrant(array('access'));
        $this->checkAccessDeniedException('index', false);
        $this->checkAuthChecker(0, $id, 'access');
    }


}
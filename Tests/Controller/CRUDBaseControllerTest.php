<?php

namespace United\OneBundle\Tests\Controller;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use United\CoreBundle\Tests\Controller\UnitedControllerTestCase;
use United\CoreBundle\Tests\Mock\EntityRepositoryMock;
use United\OneBundle\Tests\Mock\CRUDBaseControllerMock;

class CRUDBaseControllerTest extends UnitedControllerTestCase
{

    /**
     * @var EntityRepositoryMock $repository
     */
    private $repository;

    protected function setUp()
    {
        $this->controller = new CRUDBaseControllerMock();
        $this->repository = new EntityRepositoryMock();
        $this->controller->mock_repository = $this->repository;
        parent::setUp();
    }

    /**
     * Check generation of all routes.
     */
    public function testRoutes()
    {
        // Check controller routes
        $this->checkControllerRoutes(array(

            // index route
            $this->getClassPrefix() . '.index' => array(
                'path' => '/United_OneBundle_Tests_Controller_CRUDBaseControllerTest/',
                'defaults' => array(
                    '_controller' => 'United\OneBundle\Tests\Mock\CRUDBaseControllerMock::indexAction',
                ),
            ),

            // create route
            $this->getClassPrefix() . '.create' => array(
                'path' => '/United_OneBundle_Tests_Controller_CRUDBaseControllerTest/create',
                'defaults' => array(
                    '_controller' => 'United\OneBundle\Tests\Mock\CRUDBaseControllerMock::createAction',
                ),
            ),

            // update route
            $this->getClassPrefix() . '.update' => array(
                'path' => '/United_OneBundle_Tests_Controller_CRUDBaseControllerTest/{id}/update',
                'defaults' => array(
                    '_controller' => 'United\OneBundle\Tests\Mock\CRUDBaseControllerMock::updateAction',
                ),
            ),

            // delete route
            $this->getClassPrefix() . '.delete' => array(
                'path' => '/United_OneBundle_Tests_Controller_CRUDBaseControllerTest/{id}/delete',
                'defaults' => array(
                    '_controller' => 'United\OneBundle\Tests\Mock\CRUDBaseControllerMock::deleteAction',
                ),
            ),

            // root redirect route
            'united.' . $this->getClassPrefix() => array(
                'path' => '/',
                'defaults' => array(
                    '_controller' => 'Symfony\Bundle\FrameworkBundle\Controller\RedirectController::redirectAction',
                    'route' => $this->getClassPrefix() . '.index',
                    'permanent' => true,
                ),
            ),
        ));
    }

    /**
     * Calling indexAction on CRUDBaseController should render the template, set
     * by getTemplateForAction('index').
     */
    public function testIndexAction()
    {

        // Test sending emptyrequest
        $request = new Request();
        $this->container->enterScope('request');
        $this->container->set('request', $request, 'request');

        // Check index template rendering: dump context
        $context = json_decode($this->getActionContent('index'), true);
        $this->assertArrayHasKey('entities', $context);
        $this->assertArrayHasKey('layout', $context);
    }

    /**
     * Calling createAction on CRUDBaseController should render the create form.
     */
    public function testCreateAction()
    {

        // Test sending emptyrequest
        $request = new Request();
        $this->container->enterScope('request');
        $this->container->set('request', $request, 'request');
        $this->container->get('request_stack')->push($request);

        // Check index template rendering: dump context
        $this->assertEquals(200, $this->getControllerActionResponse('create', array($request))->getStatusCode());
    }

    /**
     * Calling updateAction on CRUDBaseController should render the update form.
     */
    public function testUpdateAction()
    {

        // Test sending emptyrequest
        $request = new Request();
        $this->container->enterScope('request');
        $this->container->set('request', $request, 'request');
        $this->container->get('request_stack')->push($request);

        // Check index template rendering: dump context
        $this->assertEquals(200, $this->getControllerActionResponse('update', array(1, $request))->getStatusCode());
    }

    /**
     * Calling deleteAction on CRUDBaseController should render the delete form.
     */
    public function testDeleteAction()
    {

        // Test sending emptyrequest
        $request = new Request();
        $this->container->enterScope('request');
        $this->container->set('request', $request, 'request');
        $this->container->get('request_stack')->push($request);

        // Check index template rendering: dump context
        $this->assertEquals(200, $this->getControllerActionResponse('delete', array(1, $request))->getStatusCode());
        $crawler = new Crawler($this->getActionContent('delete', array(1, $request)));

        // The CRUDBaseController delete form should contain the united delete form
        $form_names = $crawler->filter('form')->extract(array('name'));
        $this->assertEquals(array('united_one_delete_form'), $form_names);
    }


}
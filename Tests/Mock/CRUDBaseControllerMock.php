<?php

namespace United\OneBundle\Tests\Mock;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Form;
use United\CoreBundle\Tests\Mock\EntityMock;
use United\OneBundle\Controller\CRUDBaseController;

class CRUDBaseControllerMock extends CRUDBaseController
{
    /**
     * @var EntityRepository $mock_repository
     */
    public $mock_repository;


    /**
     * Returns the entity repository for the CRUD operations.
     *
     * @return EntityRepository
     */
    protected function getEntityRepository()
    {
        return $this->mock_repository;
    }

    /**
     * Returns a new entity.
     *
     * @return object
     */
    protected function createNewEntity()
    {
        return new EntityMock();
    }

    /**
     * Returns the template for the given action. For the base implementation,
     * $action can be: index|create|update|delete.
     *
     * @param string $action the action to get the twig template for
     * @return string the twig template to render
     */
    protected function getTemplateForAction($action)
    {
        if($action != 'index') {
            return 'UnitedCoreBundle:Tests:Form.html.twig';
        }
        return parent::getTemplateForAction($action);
    }

    protected function templateForIndexAction()
    {
        return 'UnitedCoreBundle:Tests:DumpContext.html.twig';
    }

    /**
     * Returns the form the given action. For the base implementation,
     * $action can be: index|create|update|delete.
     *
     * @param string $action
     * @param null|object $entity
     * @return string|Form
     */
    protected function getFormForAction($action, $entity = null)
    {
        if($action != 'delete') {
            return $this->createFormBuilder()->add($action, 'text')->getForm();
        }
        return parent::getFormForAction($action, $entity);
    }
}
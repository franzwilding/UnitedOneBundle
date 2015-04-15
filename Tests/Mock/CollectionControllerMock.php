<?php

namespace United\OneBundle\Tests\Mock;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Form;
use United\OneBundle\Controller\CollectionBaseController;
use United\OneBundle\Tests\tests\Entities\Mock;

class CollectionControllerMock extends CollectionBaseController
{
    public $form;

    /**
     * Returns the entity repository for the CRUD operations.
     *
     * @return EntityRepository
     */
    protected function getEntityRepository() {
        return $this->getDoctrine()->getManager()->getRepository('UnitedOneBundle:Mock');
    }

    /**
     * Returns a new entity.
     *
     * @return Mock
     */
    protected function createNewEntity() {
        return new Mock();
    }

    /**
     * This method can alter the context for each action, that is passed to the
     * twig template.
     *
     * @param string $action
     * @param array $context
     * @return array
     */
    protected function alterContextForAction($action, &$context)
    {
        parent::alterContextForAction($action, $context);
        $context['layout'] = 'layout-embed.html.twig';
    }

    /**
     * Calls any internal function.
     *
     * @param string $name
     * @param array $parameters
     * @return mixed
     */
    public function getM($name, $parameters = array())
    {
        return call_user_func_array(array($this, $name), $parameters);
    }

    /**
     * @param null|object $entity
     * @return Form
     */
    protected function formForCreateAction($entity)
    {
        return $this->createFormBuilder($entity)
          ->add('title')
          ->add('save', 'submit_or_delete')
          ->getForm();
    }

    /**
     * @param null|object $entity
     * @return Form
     */
    protected function formForUpdateAction($entity)
    {
        return $this->createFormBuilder($entity)
          ->add('title')
          ->add('save', 'submit_or_delete')
          ->getForm();
    }
}
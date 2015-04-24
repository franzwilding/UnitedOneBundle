<?php

namespace United\OneBundle\Controller;

use Symfony\Component\Form\Form;

use United\CoreBundle\Controller\CRUDController;
use United\OneBundle\Form\DeleteFormType;

/**
 * Class CRUDController
 * Defines the base CRUD Controller for United One.
 * @package United\OneBundle\Controller
 */
abstract class CRUDBaseController extends CRUDController
{

    /**
     * Returns the form for the delete action.
     *
     * @param null|object $entity
     * @return string|Form
     */
    protected function formForDeleteAction($entity = null)
    {
        return $this->createForm(new DeleteFormType(), $entity);
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

        if ($this->container->get('request')->query->get('embed')) {
            $context['layout'] = 'layout-embed.html.twig';
        } else {
            $context['layout'] = 'layout.html.twig';
        }
    }

    /**
     * Returns the template for the create action.
     * @return string the twig template to render
     */
    protected function templateForCreateAction()
    {
        return 'UnitedOneBundle:Form:form.html.twig';
    }

    /**
     * Returns the template for the update action.
     * @return string the twig template to render
     */
    protected function templateForUpdateAction()
    {
        return 'UnitedOneBundle:Form:form.html.twig';

    }

    /**
     * Returns the template for the delete action.
     * @return string the twig template to render
     */
    protected function templateForDeleteAction()
    {
        return 'UnitedOneBundle:Form:form.html.twig';
    }

    /**
     * Returns the template for the error action.
     * @return string the twig template to render
     */
    protected function templateForErrorAction()
    {
        return 'UnitedOneBundle:Error:error.html.twig';
    }
}
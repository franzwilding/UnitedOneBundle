<?php

namespace United\OneBundle\Controller;

use Symfony\Component\Form\Form;
use United\CoreBundle\Controller\CollectionController;
use United\OneBundle\Form\DeleteFormType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class CollectionController
 * Defines a controller to display collections containing content provided by
 * another controller.
 * @package United\OneBundle\Controller
 */
abstract class CollectionBaseController extends CollectionController
{
    /**
     * Returns the form the delete action.
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
     * Returns the template for the index action.
     * @return string the twig template to render
     */
    protected function templateForIndexAction()
    {
        return 'UnitedOneBundle:Collection:index.html.twig';
    }

    /**
     * Returns the template for the index action.
     * @return string the twig template to render
     */
    protected function templateForViewAction()
    {
        return 'UnitedOneBundle:Collection:view.html.twig';
    }

    /**
     * Returns the template for the index action.
     * @return string the twig template to render
     */
    protected function templateForItemAction()
    {
        return 'UnitedOneBundle:Collection:item.html.twig';
    }

    /**
     * Returns the template for the index action.
     * @return string the twig template to render
     */
    protected function templateForCreateAction()
    {
        return 'UnitedOneBundle:Form:form.html.twig';
    }

    /**
     * Returns the template for the index action.
     * @return string the twig template to render
     */
    protected function templateForUpdateAction()
    {
        return 'UnitedOneBundle:Form:form.html.twig';
    }

    /**
     * Returns the template for the index action.
     * @return string the twig template to render
     */
    protected function templateForDeleteAction()
    {
        return 'UnitedOneBundle:Form:form.html.twig';
    }
}
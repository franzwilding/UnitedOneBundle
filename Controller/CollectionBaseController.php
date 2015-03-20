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
     * Returns the form the given action. For the base implementation,
     * $action can be: index|create|update|delete.
     *
     * @param string $action
     * @param null|object $entity
     * @return string|Form
     */
    protected function getFormForAction($action, $entity = null)
    {
        if ($action == 'delete') {
            return $this->createForm(new DeleteFormType(), $entity);
        }

        return NULL;
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
     * Returns the template for the given action. For the base implementation,
     * $action can be: index|create|update|delete.
     *
     * @param string $action the action to get the twig template for
     * @return string the twig template to render
     */
    protected function getTemplateForAction($action)
    {
        switch ($action) {
            case 'index':
                return 'UnitedOneBundle:Collection:index.html.twig';
                break;
            case 'view':
                return 'UnitedOneBundle:Collection:view.html.twig';
                break;
            case 'item':
                return 'UnitedOneBundle:Collection:item.html.twig';
                break;
            case 'create':
                return 'UnitedOneBundle:Form:form.html.twig';
                break;
            case 'update':
                return 'UnitedOneBundle:Form:form.html.twig';
                break;
            case 'delete':
                return 'UnitedOneBundle:Form:form.html.twig';
                break;
            default:
                return parent::getTemplateForAction($action);
                break;
        }
    }
}
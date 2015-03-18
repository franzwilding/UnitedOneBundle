<?php

namespace United\OneBundle\Controller;

/**
 * Class TableController
 * Defines a CRUD Controller that displays a table for the index action.
 * @package United\OneBundle\Controller
 */
abstract class TableController extends CRUDBaseController
{

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
                return 'UnitedOneBundle:Table:index.html.twig';
                break;
            case 'ajax':
                return 'UnitedOneBundle:Table:ajax.html.twig';
                break;
            case 'row':
                return 'UnitedOneBundle:Table:row.html.twig';
                break;
            default:
                return parent::getTemplateForAction($action);
                break;
        }
    }

    protected function alterContextForAction($action, &$context)
    {
        parent::alterContextForAction($action, $context);

        if ($action == 'index') {
            $context['rowTemplate'] = $this->getTemplateForAction('row');
            $context['descriptionTemplate'] = $this->getTemplateForAction('description');
        }
    }
}
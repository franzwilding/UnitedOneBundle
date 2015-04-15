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
     * Returns the template for the index action.
     * @return string the twig template to render
     */
    protected function templateForIndexAction()
    {
        return 'UnitedOneBundle:Table:index.html.twig';
    }

    /**
     * Returns the template for the description action.
     * @return string the twig template to render
     */
    protected function templateForDescriptionAction()
    {
        return null;
    }

    /**
     * Returns the template for the row action.
     * @return string the twig template to render
     */
    protected function templateForRowAction()
    {
        return 'UnitedOneBundle:Table:row.html.twig';
    }

    protected function alterContextForAction($action, &$context)
    {
        parent::alterContextForAction($action, $context);

        if ($action == 'index') {
            $context['rowTemplate'] = $this->getTemplateForAction('row');
            $context['descriptionTemplate'] = $this->getTemplateForAction(
              'description'
            );
        }
    }
}
<?php

namespace United\OneBundle\Controller;

/**
 * Class CardController
 * Defines a CRUD Controller that displays cards for the index action.
 * @package United\OneBundle\Controller
 */
abstract class CardController extends CRUDBaseController
{

    /**
     * Returns the template for the index action.
     * @return string the twig template to render
     */
    protected function templateForIndexAction()
    {
        return 'UnitedOneBundle:Card:index.html.twig';
    }

    /**
     * Returns the template for the card action.
     * @return string the twig template to render
     */
    protected function templateForCardAction()
    {
        return 'UnitedOneBundle:Card:card.html.twig';
    }

    protected function alterContextForAction($action, &$context)
    {
        parent::alterContextForAction($action, $context);

        if ($action == 'index') {
            $context['cardTemplate'] = $this->getTemplateForAction('card');
        }
    }
}
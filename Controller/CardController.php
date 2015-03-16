<?php

namespace United\OneBundle\Controller;

/**
 * Class CardController
 * Defines a CRUD Controller that displays cards for the index action.
 * @package United\OneBundle\Controller
 */
abstract class CardController extends CRUDBaseController {

  /**
   * Returns the template for the given action. For the base implementation,
   * $action can be: index|create|update|delete.
   *
   * @param string $action the action to get the twig template for
   * @return string the twig template to render
   */
  protected function getTemplateForAction($action) {
    switch($action) {
      case 'index': return 'UnitedOneBundle:Card:index.html.twig'; break;
      case 'ajax': return 'UnitedOneBundle:Card:ajax.html.twig'; break;
      case 'card': return 'UnitedOneBundle:Card:card.html.twig'; break;
      default: return parent::getTemplateForAction($action); break;
    }
  }

  protected function alterContextForAction($action, &$context) {
    parent::alterContextForAction($action, $context);

    if($action == 'index') {
      $context['cardTemplate'] = $this->getTemplateForAction('card');
    }
  }
}
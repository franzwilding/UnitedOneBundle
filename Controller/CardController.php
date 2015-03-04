<?php

namespace United\OneBundle\Controller;

use Symfony\Component\Form\FormTypeInterface;
use United\CoreBundle\Controller\AjaxCRUDController;

use United\OneBundle\Form\DeleteFormType;

/**
 * Class CardController
 * Defines an CRUD Controller that displays cards for the index action.
 * @package United\OneBundle\Controller
 */
abstract class CardController extends AjaxCRUDController {

  /**
   * Returns the form the given action. For the base implementation,
   * $action can be: index|create|update|delete.
   *
   * @param string $action
   * @return string|FormTypeInterface
   */
  protected function getFormForAction($action) {
    if($action == 'delete') {
      return new DeleteFormType();
    }

    return NULL;
  }

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
      case 'view': return 'UnitedOneBundle:Card:view.html.twig'; break;
      case 'create': return 'UnitedOneBundle:Form:form.html.twig'; break;
      case 'update': return 'UnitedOneBundle:Form:form.html.twig'; break;
      case 'delete': return 'UnitedOneBundle:Form:form.html.twig'; break;
      case 'card': return 'UnitedOneBundle:Card:card.html.twig'; break;
      default: return NULL; break;
    }
  }

  protected function alterContextForAction($action, &$context) {
    if($action == 'index') {
      $context['cardTemplate'] = $this->getTemplateForAction('card');
    }
  }
}
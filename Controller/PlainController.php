<?php

namespace United\OneBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;

use United\CoreBundle\Controller\UnitedController;

/**
 * Class PlainController
 * Defines an index action and renders a template.
 * @package United\OneBundle\Controller
 */
abstract class PlainController extends UnitedController {

  /**
   * @return string the twig template to render
   */
  abstract protected function template();

  /**
   * @Route("/")
   */
  public function indexAction() {
    return $this->render($this->template());
  }
}
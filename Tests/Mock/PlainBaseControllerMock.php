<?php

namespace United\OneBundle\Tests\Mock;

use United\OneBundle\Controller\PlainBaseController;

class PlainBaseControllerMock extends PlainBaseController
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
        return "UnitedCoreBundle:Tests:DumpContext.html.twig";
    }
}
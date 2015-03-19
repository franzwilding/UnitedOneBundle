<?php

namespace United\OneBundle\Tests\Mock;

use United\OneBundle\Controller\PlainBaseController;

class PlainBaseControllerMock extends PlainBaseController
{

    public $context;

    /**
     * @var string $mock_template
     */
    public $mock_template;

    protected function alterContextForAction($action, &$context)
    {
        parent::alterContextForAction($action, $context);
        $this->context = $context;
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
        return "UnitedCoreBundle:Tests:PlainControllerIndex.html.twig";
    }
}
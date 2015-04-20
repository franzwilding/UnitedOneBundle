<?php

namespace United\OneBundle\Tests\Mock;

use Doctrine\ORM\EntityRepository;
use United\OneBundle\Controller\SectionController;

class SectionControllerMock extends SectionController
{
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
        $context['layout'] = 'layout-embed.html.twig';
    }

    /**
     * Calls any internal function.
     *
     * @param string $name
     * @param array $parameters
     * @return mixed
     */
    public function getM($name, $parameters = array())
    {
        return call_user_func_array(array($this, $name), $parameters);
    }
}
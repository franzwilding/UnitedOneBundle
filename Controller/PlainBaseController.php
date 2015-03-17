<?php

namespace United\OneBundle\Controller;

use United\CoreBundle\Controller\PlainController;

abstract class PlainBaseController extends PlainController {

    /**
     * This method can alter the context for each action, that is passed to the
     * twig template.
     *
     * @param string $action
     * @param array $context
     * @return array
     */
    protected function alterContextForAction($action, &$context) {
        parent::alterContextForAction($action, $context);

        if($this->container->get('request')->query->get('embed')) {
            $context['layout'] = 'layout-embed.html.twig';
        } else {
            $context['layout'] = 'layout.html.twig';
        }
    }
}
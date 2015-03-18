<?php

namespace United\OneBundle\Controller;

class SectionController extends PlainBaseController
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
        if ($action == 'section') {
            return 'UnitedOneBundle:Section:section.html.twig';
        }
        return 'UnitedOneBundle:Section:index.html.twig';
    }

    protected function alterContextForAction($action, &$context)
    {
        parent::alterContextForAction($action, $context);
        $context['sectionTemplate'] = $this->getTemplateForAction('section');
    }
}
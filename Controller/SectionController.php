<?php

namespace United\OneBundle\Controller;

class SectionController extends PlainBaseController
{

    /**
     * Returns the template for the index action.
     * @return string the twig template to render
     */
    protected function templateForIndexAction()
    {
        return 'UnitedOneBundle:Section:index.html.twig';
    }

    /**
     * Returns the template for the section action.
     * @return string the twig template to render
     */
    protected function templateForSectionAction()
    {
        return 'UnitedOneBundle:Section:section.html.twig';
    }

    protected function alterContextForAction($action, &$context)
    {
        parent::alterContextForAction($action, $context);
        $context['sectionTemplate'] = $this->getTemplateForAction('section');
    }
}
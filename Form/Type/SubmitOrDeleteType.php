<?php

namespace United\OneBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SubmitOrDeleteType extends SubmitType
{

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);
        $view->vars['render_preview'] = $options['render_preview'];
        $view->vars['preview_embed'] = $options['preview_embed'];
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(
          array(
            'render_preview'    => true,
            'preview_embed'     => true,
          )
        );
    }

    public function getParent()
    {
        return 'submit';
    }

    public function getName()
    {
        return 'submit_or_delete';
    }
}
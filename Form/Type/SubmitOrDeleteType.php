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
        $view->vars['render_delete'] = $options['render_delete'];

        if($options['cancel_url']) {
            $view->vars['cancel_url'] = $options['cancel_url'];
        }

        if($options['delete_url']) {
            $view->vars['delete_url'] = $options['delete_url'];
        }
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
            'cancel_url'        => null,
            'delete_url'        => null,
            'render_delete'     => true,
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
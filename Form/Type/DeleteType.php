<?php

namespace United\OneBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DeleteType extends SubmitType
{

    public function getParent()
    {
        return 'submit';
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        if($options['cancel_url']) {
            $view->vars['cancel_url'] = $options['cancel_url'];
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
            'cancel_url'        => null,
          )
        );
    }

    public function getName()
    {
        return 'delete';
    }
}
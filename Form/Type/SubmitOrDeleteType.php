<?php

namespace United\OneBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class SubmitOrDeleteType extends SubmitType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$view->vars['data'] = $builder->getData();
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
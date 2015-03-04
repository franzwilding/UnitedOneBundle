<?php

namespace United\OneBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class DeleteFormType extends AbstractType
{
  /**
   * @param FormBuilderInterface $builder
   * @param array $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('delete', 'delete');
  }

  /**
   * @return string
   */
  public function getName()
  {
    return 'united_one_delete_form';
  }
}

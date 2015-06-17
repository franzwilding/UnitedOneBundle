<?php

namespace United\OneBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FormTypeGridExtension extends AbstractTypeExtension
{

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        // field classes
        if(!array_key_exists('field_classes', $view->vars)) {
            $view->vars['field_classes'] = array();
        }

        // display this field inline
        if($options['field_inline']) {
            $view->vars['field_classes'][] = 'inline';
        }

        // set the grid width for this field
        if($options['field_width']) {
            switch($options['field_width']) {
                case 1  : $width = 'one'; break;
                case 2  : $width = 'two'; break;
                case 3  : $width = 'three'; break;
                case 4  : $width = 'four'; break;
                case 5  : $width = 'five'; break;
                case 6  : $width = 'six'; break;
                case 7  : $width = 'seven'; break;
                case 8  : $width = 'eight'; break;
                case 9  : $width = 'nine'; break;
                case 10 : $width = 'ten'; break;
                case 11 : $width = 'eleven'; break;
                case 12 : $width = 'twelve'; break;
                case 13 : $width = 'thirteen'; break;
                case 14 : $width = 'fourteen'; break;
                case 15 : $width = 'fifteen'; break;
                case 16 : $width = 'sixteen'; break;
                default : $width = null;
            }

            if($width) {
                $view->vars['field_classes'][] = $width;
                $view->vars['field_classes'][] = 'wide';
            }
        }
    }

    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType()
    {
        return 'form';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
          'field_inline'      => false,
          'group_inline'      => false,
          'field_width'       => null,
        ));
    }
}
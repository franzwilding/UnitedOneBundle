<?php

namespace United\OneBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class SortType extends HiddenType
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'hidden';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'united_sort';
    }
}
<?php

namespace United\OneBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DeleteType extends SubmitType {

  public function getParent() {
    return 'submit';
  }

  public function getName() {
    return 'delete';
  }
}
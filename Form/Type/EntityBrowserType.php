<?php

namespace United\OneBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\DoctrineType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\Form\ChoiceList\ORMQueryBuilderLoader;

class EntityBrowserType extends DoctrineType
{

    public function buildView(
      FormView $view,
      FormInterface $form,
      array $options
    ) {
        parent::buildView($view, $form, $options);
    }

    /**
     * Return the default loader object.
     *
     * @param ObjectManager $manager
     * @param mixed $queryBuilder
     * @param string $class
     *
     * @return ORMQueryBuilderLoader
     */
    public function getLoader(ObjectManager $manager, $queryBuilder, $class)
    {
        return new ORMQueryBuilderLoader(
          $queryBuilder,
          $manager,
          $class
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'united_entity_browser';
    }
}
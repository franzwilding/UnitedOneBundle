<?php

namespace United\OneBundle\Form\Type;

use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Config\Definition\Exception\Exception;
use United\OneBundle\EventListener\ExistingTagsEventListener;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TagsType extends CollectionType
{

    /**
     * @var ManagerRegistry
     */
    protected $registry;

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Returns select_options from $options array or use entity manager to get all entities for tag_data_class.
     *
     * @param $options
     * @return array
     */
    public function getAllSelectOptions($options)
    {
        if ($options['select_options']) {
            return $options['select_options'];
        }

        $em = $this->registry->getManagerForClass($options['tag_data_class']);

        return $em->getRepository($options['tag_data_class'])->findAll();
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(
      FormView $view,
      FormInterface $form,
      array $options
    ) {
        parent::buildView($view, $form, $options);
        $view->vars['select_options'] = $this->getAllSelectOptions($options);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!$options['tag_data_class']) {
            throw new Exception(
              'united_tags attribute: "tag_data_class" can\'t be null!'
            );
        }

        if (!$options['type']) {
            throw new Exception(
              'united_tags attribute: "type" can\'t be null!'
            );
        }

        parent::buildForm($builder, $options);
        $builder->addEventSubscriber(
          new ExistingTagsEventListener($this->getAllSelectOptions($options))
        );
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(
          array(
            'allow_add'         => true,
            'allow_delete'      => true,
            'by_reference'      => true,
            'select_options'    => null,
            'type'              => null,
            'tag_data_class'    => null,
          )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'collection';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'united_tags';
    }
}
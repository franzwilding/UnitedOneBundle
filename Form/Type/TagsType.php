<?php

namespace United\OneBundle\Form\Type;

use Doctrine\Common\Persistence\ManagerRegistry;
use United\OneBundle\EventListener\UniqueTagsEventListener;
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

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function getDataClass()
    {
        return 'Food\Bundle\AdminBundle\Entity\Category';
    }

    public function getAllOptions()
    {
        $em = $this->registry->getManagerForClass($this->getDataClass());
        return $em->getRepository($this->getDataClass())->findAll();
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $view->vars['select_options'] = $options['select_options'];
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->addEventSubscriber(new UniqueTagsEventListener($this->getAllOptions()));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            'type'              => new TagType($this->getDataClass()),
            'allow_add'         => true,
            'allow_delete'      => true,
            'by_reference'      => true,
            'select_options'    => $this->getAllOptions(),
        ));
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
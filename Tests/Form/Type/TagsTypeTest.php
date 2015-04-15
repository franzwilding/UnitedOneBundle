<?php

namespace United\OneBundle\Tests\Form\Type;

use appTestDebugProjectContainer;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use United\OneBundle\Form\Type\DeleteType;
use United\OneBundle\Form\Type\TagsType;

class TagsTypeTest extends KernelTestCase
{

    /**
     * @var appTestDebugProjectContainer $container
     */
    private $container;

    public function testCreateSubmitOrDeleteButtonInstances()
    {
        $class = 'Symfony\Component\Form\Extension\Core\Type\CollectionType';

        // try to create an united_tags form without tag_data_class option
        $msg = '';
        try {
            $form = $this->container->get('form.factory')->create(
              'united_tags'
            );
        } catch (\Exception $e) {
            $msg = $e->getMessage();
        }

        $this->assertEquals(
          'united_tags attribute: "tag_data_class" can\'t be null!',
          $msg
        );


        $form = $this->container->get('form.factory')->create(
          'united_tags',
          null,
          array(
            'tag_data_class' => 'United\OneBundle\Tests\tests\Entities\Mock'
          )
        );

        $this->assertEquals('united_tags', $form->getName());
    }

    public function testTagsCanBeAddedToForm()
    {
        $form = $this->container->get('form.factory')
          ->createBuilder('form')
          ->getForm();

        $this->assertSame(
          $form,
          $form->add(
            'tags',
            'united_tags',
            array(
              'tag_data_class' => 'United\OneBundle\Tests\tests\Entities\Mock'
            )
          )
        );
    }

    public function testFormType()
    {

        $type = new TagsType($this->container->get('doctrine'));
        $form = $this->container->get('form.factory')->create(
          $type,
          null,
          array(
            'tag_data_class' => 'United\OneBundle\Tests\tests\Entities\Mock'
          )
        );
        $this->assertTrue($form->isSynchronized());

    }

    protected function setUp()
    {
        self::bootKernel();
        $this->container = static::$kernel->getContainer();
        $em = static::$kernel->getContainer()->get('doctrine')->getManager();
        $schemaTool = new SchemaTool($em);
        $metadata = array(
          $em->getClassMetadata('United\OneBundle\Tests\tests\Entities\Mock'),
        );

        // Drop and recreate tables for Mock entity
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }
}
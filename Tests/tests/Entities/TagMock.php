<?php

namespace United\OneBundle\Tests\tests\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Tests\Fixtures\EntityInterface;

/**
 * @ORM\Table()
 * @ORM\Entity
 */
class TagMock implements EntityInterface
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    protected $title;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="TagsMock", mappedBy="children")
     */
    private $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->title;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return ArrayCollection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param ArrayCollection $categories
     * @return TagMock
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @param TagMock $tag
     * @return TagMock
     */
    public function addTag($tag)
    {
        $this->tags->add($tag);
        return $this;
    }

    public function getName()
    {
        return $this->title;
    }

    public function setName($name)
    {
        $this->title = $name;
    }

}
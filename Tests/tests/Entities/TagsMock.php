<?php

namespace United\OneBundle\Tests\tests\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use United\CoreBundle\Model\EntityInterface;

/**
 * @ORM\Table()
 * @ORM\Entity
 */
class TagsMock implements EntityInterface
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
     * @ORM\ManyToMany(targetEntity="TagMock", inversedBy="tags", cascade={"persist"})
     * @ORM\JoinTable(name="tags_children")
     */
    private $children;

    public function __construct()
    {
        $this->children = new ArrayCollection();
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
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param ArrayCollection $children
     * @return TagsMock
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }

    /**
     * @param TagMock $child
     * @return TagsMock
     */
    public function addChild($child)
    {
        $this->children->add($child);
        return $this;
    }

}
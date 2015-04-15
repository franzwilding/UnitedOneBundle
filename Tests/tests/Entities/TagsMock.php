<?php

namespace United\OneBundle\Tests\tests\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity
 */
class TagsMock extends Mock
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
    private $title;

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

    /**
     * @return ArrayCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param ArrayCollection $categories
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
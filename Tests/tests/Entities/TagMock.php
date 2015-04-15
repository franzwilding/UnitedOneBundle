<?php

namespace United\OneBundle\Tests\tests\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity
 */
class TagMock extends Mock
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
     * @ORM\ManyToMany(targetEntity="TagsMock", mappedBy="children")
     */
    private $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
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

}
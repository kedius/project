<?php

namespace CMS\BackendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Tags
 *
 * @ORM\Table(name="tags", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_tags_articles1_idx", columns={"content_id"})})
 * @ORM\Entity(repositoryClass="CMS\BackendBundle\Entity\Repository\TagsRepository")
 */
class Tags
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity = "Content")
     * @ORM\JoinTable(name="content_to_tags",
     *   joinColumns={@ORM\JoinColumn(name="tags_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="content_id", referencedColumnName="id")}
     *   )
     * @var ArrayCollection $contents;
     */
    private $contents;


    public function __construct()
    {
        $this->contents = new ArrayCollection();
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Name
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add content
     */
    public function addContents($contents)
    {
        $this->contents->add($contents);
    }

    /**
     * Set content
     */
    public function setContents($contents)
    {
        $this->contents = $contents;
    }

    /**
     * Get content
     */
    public function getContents()
    {
        return $this->contents->toArray();
    }
}

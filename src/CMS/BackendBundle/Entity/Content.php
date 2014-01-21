<?php

namespace CMS\BackendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Content
 *
 * @ORM\Table(name="content", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_articles_user1_idx", columns={"author"}), @ORM\Index(name="fk_articles_statuses1_idx", columns={"status"}), @ORM\Index(name="fk_articles_categories1_idx", columns={"category"}), @ORM\Index(name="fk_articles_user2_idx", columns={"publisher"}), @ORM\Index(name="fk_content_content_types1_idx", columns={"content_type"})})
 * @ORM\Entity(repositoryClass="CMS\BackendBundle\Entity\Repository\ContentRepository")
 */
class Content
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
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="alias", type="string", length=255, nullable=false)
     */
    private $alias;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text", nullable=false)
     */
    private $text;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_at", type="datetime", nullable=false)
     */
    private $dateAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="public_date", type="datetime", nullable=true)
     */
    private $publicDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="rating", type="integer", nullable=false)
     */
    private $rating = '0';

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="author", referencedColumnName="id")
     * })
     */
    private $author;

    /**
     * @var \Statuses
     *
     * @ORM\ManyToOne(targetEntity="Statuses")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="status", referencedColumnName="id")
     * })
     */
    private $status;

    /**
     * @var \Category
     *
     * @ORM\ManyToOne(targetEntity="Categories")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category", referencedColumnName="id")
     * })
     */
    private $category;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="publisher", referencedColumnName="id")
     * })
     */
    private $publisher;

    /**
     * @var \ContentTypes
     *
     * @ORM\ManyToOne(targetEntity="ContentTypes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="content_type", referencedColumnName="id")
     * })
     */
    private $contentType;


    /**
     * @ORM\ManyToMany(targetEntity = "Tags", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="content_to_tags",
     *   joinColumns={@ORM\JoinColumn(name="content_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="tags_id", referencedColumnName="id")}
     *   )
     * @var ArrayCollection $tags;
     */
    private $tags;

    /**
     * @ORM\OneToMany(
     *   targetEntity="Comments",
     *   mappedBy="content",
     *   cascade={"persist", "remove"}
     * )
     * @var ArrayCollection $comments;
     */
    private $comments;

    /**
     * @ORM\OneToMany(
     *   targetEntity="Files",
     *   mappedBy="content",
     *   cascade={"persist", "remove"}
     * )
     *
     * @var ArrayCollection $files;
     */
    private $files;

    /**
     * @ORM\OneToOne(
     *   targetEntity="ContentReview",
     *   mappedBy="content",
     *   cascade={"persist", "remove"}
     * )
     * @var ArrayCollection $review;
     */
    private $review;



    public function __construct() {
        $this->tags = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->files = new ArrayCollection();
        $this->dateAt = new \DateTime();
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
     * Set title
     *
     * @param string $title
     * @return Content
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set alias
     *
     * @param string $alias
     * @return Content
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Content
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set dateAt
     *
     * @param \DateTime $dateAt
     * @return Content
     */
    public function setDateAt($dateAt)
    {
        $this->dateAt = $dateAt;

        return $this;
    }

    /**
     * Get dateAt
     *
     * @return \DateTime 
     */
    public function getDateAt()
    {
        return $this->dateAt;
    }

    /**
     * Set publicDate
     *
     * @param \DateTime $publicDate
     * @return Content
     */
    public function setPublicDate($publicDate)
    {
        $this->publicDate = $publicDate;

        return $this;
    }

    /**
     * Get publicDate
     *
     * @return \DateTime 
     */
    public function getPublicDate()
    {
        return $this->publicDate;
    }

    /**
     * Set rating
     *
     * @param integer $rating
     * @return Content
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return integer 
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set author
     *
     * @param \CMS\BackendBundle\Entity\User $author
     * @return Content
     */
    public function setAuthor(\CMS\BackendBundle\Entity\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \CMS\BackendBundle\Entity\User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set status
     *
     * @param \CMS\BackendBundle\Entity\Statuses $status
     * @return Content
     */
    public function setStatus(\CMS\BackendBundle\Entity\Statuses $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \CMS\BackendBundle\Entity\Statuses
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set category
     *
     * @param \CMS\BackendBundle\Entity\Categories $category
     * @return Content
     */
    public function setCategory(\CMS\BackendBundle\Entity\Categories $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \CMS\BackendBundle\Entity\Categories
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set publisher
     *
     * @param \CMS\BackendBundle\Entity\User $publisher
     * @return Content
     */
    public function setPublisher(\CMS\BackendBundle\Entity\User $publisher = null)
    {
        $this->publisher = $publisher;

        return $this;
    }

    /**
     * Get publisher
     *
     * @return \CMS\BackendBundle\Entity\User
     */
    public function getPublisher()
    {
        return $this->publisher;
    }

    /**
     * Set contentType
     *
     * @param \CMS\BackendBundle\Entity\ContentTypes $contentType
     * @return Content
     */
    public function setContentType(\CMS\BackendBundle\Entity\ContentTypes $contentType = null)
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * Get contentType
     *
     * @return \CMS\BackendBundle\Entity\ContentTypes
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    public function setTags($tags) {
        $this->tags = $tags;
    }

    public function getTags() {
        return $this->tags->toArray();
    }

    public function getTagsList() {
        return $this->tags;
    }

    public function addTags($tag) {
        if (! $this->tags->contains($tag)) {
            $this->tags->add($tag);
        }
    }

    public function getComments() {
        return $this->comments->toArray();
    }

    public function setFiles($files) {
        $this->files = $files;
    }

    public function getFiles() {
        return $this->files;
    }

    public function checkFiles() {
        foreach ($this->files->toArray() as $key => $file) {
            if ($file->getContent() == null) {
                $this->files->remove($key);
            }
        }
    }

    public function setReview($review) {
        $this->review = $review;
    }

    public function getReview() {
        return $this->review;
    }
}

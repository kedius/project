<?php

namespace CMS\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContentReview
 *
 * @ORM\Table(name="content_review", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_content_review_content1_idx", columns={"content_id"})})
 * @ORM\Entity
 */
class ContentReview
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
     * @ORM\Column(name="comment", type="string", length=255, nullable=false)
     */
    private $comment;

    /**
     * @var \Content
     *
     * @ORM\ManyToOne(targetEntity="Content")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="content_id", referencedColumnName="id")
     * })
     */
    private $content;



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
     * Set comment
     *
     * @param string $comment
     * @return ContentReview
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set content
     *
     * @param \CMS\BackendBundle\Entity\Content $content
     * @return ContentReview
     */
    public function setContent(\CMS\BackendBundle\Entity\Content $content = null)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return \CMS\BackendBundle\Entity\Content
     */
    public function getContent()
    {
        return $this->content;
    }
}

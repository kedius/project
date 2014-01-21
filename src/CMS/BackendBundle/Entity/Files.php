<?php

namespace CMS\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Files
 *
 * @ORM\Table(name="files", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_files_content1_idx", columns={"content_id"})})
 * @ORM\Entity
 */
class Files
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
     * @ORM\Column(name="file_name", type="string", length=255, nullable=false)
     */
    private $fileName;

    /**
     * @var string
     *
     * @ORM\Column(name="file_path", type="string", length=255, nullable=false)
     */
    private $filePath;

    /**
     * @var string
     *
     * @ORM\Column(name="mime_type", type="string", length=255, nullable=false)
     */
    private $mimeType;

    /**
     * @var \Content
     *
     * @ORM\ManyToOne(targetEntity="Content")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="content_id", referencedColumnName="id")
     * })
     */
    private $content;

    /*
     * @var file
     *
     * @Assert\File(
     *      maxSize="100k",
     *      mimeTypes = {
     *          "image/png",
     *          "image/jpeg",
     *          "image/jpg",
     *      }
     * )
     */
    protected $file;



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
     * Set fileName
     *
     * @param string $fileName
     * @return Files
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get fileName
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set filePath
     *
     * @param string $filePath
     * @return Files
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;

        return $this;
    }

    /**
     * Get filePath
     *
     * @return string 
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * Set mimeType
     *
     * @param string $mimeType
     * @return Files
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string 
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set content
     *
     * @param \CMS\BackendBundle\Entity\Content $content
     * @return Files
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

    public function setFile($file) {
        $this->file = $file;
    }
    public function getFile() {
        return $this->file;
    }
}

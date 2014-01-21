<?php

namespace CMS\BackendBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(name="login_UNIQUE", columns={"login"})})
 * @ORM\Entity(repositoryClass="CMS\BackendBundle\Entity\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
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
     * @ORM\Column(name="username", type="string", length=255, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var string
     *, mappedBy="parent"
     * @ORM\Column(name="salt", type="string", length=255, nullable=false)
     */
    private $salt;

    /**
     * @var string
     *
     * @Assert\File(
     *      maxSize="100k",
     *      mimeTypes = {
     *          "image/png",
     *          "image/jpeg",
     *          "image/jpg",
     *      }
     * )
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity = "UserRoles")
     * @ORM\JoinTable(name="user_to_roles",
     *   joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="user_roles_id", referencedColumnName="id")}
     *   )
     * @var ArrayCollection $roles;
     */
    private $roles;


    public function __construct()
    {
        $this->roles = new ArrayCollection();
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
     * Set login
     *
     * @param string $login
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get login
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return User
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    public function getUserRoles()
    {
        return $this->roles;
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    public function getRoles() {
        return $this->getUserRoles()->toArray();
    }

    public function equals(UserInterface $user)
    {
        return md5($this->getUsername()) == md5($user->getUsername());
    }

    public function eraseCredentials() {

    }

    /**
     * Serializes the content of the current User object
     * @return string
     */
    public function serialize()
    {
        return \json_encode(
            array(
                $this->id,
                $this->username,
                $this->password,
                $this->salt,
                $this->image
            )
        );
    }

    /**
     * Unserializes the given string in the current User object
     * @param serialized
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password,
            $this->salt,
            $this->image
        ) = \json_decode($serialized);
    }
}

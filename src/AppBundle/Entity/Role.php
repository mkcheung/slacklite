<?php
/**
 * Created by PhpStorm.
 * User: marscheung
 * Date: 4/8/17
 * Time: 10:00 PM
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\DateTimeType;
/**
 * @ORM\Entity
 * @ORM\Table(name="role")
 */
class Role {

    /**
     * @ORM\Id()
     * @ORM\Column(name="role_id", type = "integer")
     * @ORM\GeneratedValue(strategy = "AUTO")
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column (type = "string", length = 255)
     * @var string
     */
    protected $type;
    /**
     * @var string
     * @ORM\Column(name="createdAt", type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @var string
     * @ORM\Column(name="modifiedAt", type="datetime", nullable=false)
     */
    protected $modifiedAt;


    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="MessageUser", mappedBy="role")
     */
    protected $users;

    public function __construct($type) {

        $date = new \DateTime();
        $this->type = $type;
        $this->users     = new ArrayCollection();
        $this->createdAt = $date;
        $this->modifiedAt = $date;
    }

    /**
     *
     */
    public function setType($type) {
        $this->type = $type;
    }

    /**
     *
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    public function getId() {
        return $this->id;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Role
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set modifiedAt
     *
     * @param \DateTime $modifiedAt
     * @return Role
     */
    public function setModifiedAt($modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    /**
     * Get modifiedAt
     *
     * @return \DateTime 
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    /**
     * Add users
     *
     * @param \AppBundle\Entity\MessageUser $users
     * @return Role
     */
    public function addUser(\AppBundle\Entity\MessageUser $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \AppBundle\Entity\MessageUser $users
     */
    public function removeUser(\AppBundle\Entity\MessageUser $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }
}

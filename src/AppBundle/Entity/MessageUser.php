<?php
/**
 * Created by PhpStorm.
 * User: marscheung
 * Date: 4/8/17
 * Time: 10:01 PM
 */


namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\DateTimeType;
/**
 * @ORM\Entity
 * @ORM\Table(name="message_user")
 */
class MessageUser {

    /**
     * @ORM\Id()
     * @ORM\Column(name="user_id", type = "integer", nullable=false)
     * @ORM\GeneratedValue(strategy = "AUTO")
     * @var integer
     */
    protected $user_id;

    /**
     * @ORM\Column (type = "string", length = 255)
     * @var string
     */
    protected $username;

    /**
     * @ORM\Column (type = "string", length = 255)
     * @var string
     */
    protected $password;


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
     * @ORM\OneToMany(targetEntity="Message", mappedBy="user")
     */
    protected $sentMessages;

    /**
     * @var role
     * @ORM\ManyToOne(targetEntity="Role", inversedBy="users")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="role_id")
     */
    protected $role;


    public function __construct($username, $password, $role) {

        $date = new \DateTime();
        $this->username = $username;
        $this->setPassword($password);
        $this->messages     = new ArrayCollection();
        $this->createdAt = $date;
        $this->modifiedAt = $date;
        $this->role = $role;
        // $orm = $this->getDoctrine()->getManager();
    }

    /**
     * @param string $content
     */
    public function setPassword($password) {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }
    /**
     * @param string $content
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     *
     * @return string
     */
    public function getUserName() {
        return $this->username;
    }

    /**
     *
     * @return string
     */
    public function setUserName($username) {
        $this->username = $username;
    }

    /**
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     *
     * @return Role
     */
    public function getRole() {
        return $this->role;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return MessageUser
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
     * @return MessageUser
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
     * Add sentMessages
     *
     * @param \AppBundle\Entity\Message $sentMessages
     * @return MessageUser
     */
    public function addSentMessage(\AppBundle\Entity\Message $sentMessages)
    {
        $this->sentMessages[] = $sentMessages;

        return $this;
    }

    /**
     * Remove sentMessages
     *
     * @param \AppBundle\Entity\Message $sentMessages
     */
    public function removeSentMessage(\AppBundle\Entity\Message $sentMessages)
    {
        $this->sentMessages->removeElement($sentMessages);
    }

    /**
     * Get sentMessages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSentMessages()
    {
        return $this->sentMessages;
    }

    /**
     * Set role
     *
     * @param \AppBundle\Entity\Role $role
     * @return MessageUser
     */
    public function setRole(\AppBundle\Entity\Role $role = null)
    {
        $this->role = $role;

        return $this;
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: marscheung
 * Date: 4/8/17
 * Time: 9:59 PM
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="message", indexes={
 *     @ORM\Index(name="messageToUser", columns={"user_id"})
 * })
 */
class Message {

    /**
     * @ORM\Id()
     * @ORM\Column(name="message_id", type = "integer")
     * @ORM\GeneratedValue(strategy = "AUTO")
     * @var int
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="message", type="text", nullable=false)
     */
    protected $message;

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
     * @var user
     * @ORM\ManyToOne(targetEntity="MessageUser")
     * @ORM\JoinColumn(name="created_by_id", referencedColumnName="user_id")
     */
    protected $createdBy;

    /**
     * @var user
     * @ORM\ManyToOne(targetEntity="MessageUser", inversedBy="messages")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     */
    protected $user;

    public function __construct($message, $createdBy, $directedToUser='') {
        $this->message = $message;
        $this->createdBy = $createdBy;
        $this->user = (!empty($directedToUser)) ? $directedToUser : null;

        $this->createdAt = new \DateTime();
        $this->modifiedAt = new \DateTime();
    }


    /**
     *
     * @return string
     */
    public function getMessage() {
        return $this->message;
    }

    public function setMessage($message) {
        $this->message = $message;
    }

    /**
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function getModifiedAt() {
        return $this->modifiedAt;
    }


    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Message
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Set modifiedAt
     *
     * @param \DateTime $modifiedAt
     * @return Message
     */
    public function setModifiedAt($modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    /**
     * Set createdBy
     *
     * @param \AppBundle\Entity\MessageUser $createdBy
     * @return Message
     */
    public function setCreatedBy(\AppBundle\Entity\MessageUser $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \AppBundle\Entity\MessageUser
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\MessageUser $user
     * @return Message
     */
    public function setUser(\AppBundle\Entity\MessageUser $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\MessageUser
     */
    public function getUser()
    {
        return $this->user;
    }
}

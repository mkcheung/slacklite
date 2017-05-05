<?php
/**
 * Created by PhpStorm.
 * User: marscheung
 * Date: 4/8/17
 * Time: 9:59 PM
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="message")
 */
class Message {

    /**
     * @ORM\Id()
     * @ORM\Column(name="message_id", type = "integer")
     * @ORM\GeneratedValue(strategy = "IDENTITY")
     * @var integer
     */
    protected $message_id;

    /**
     * @var string
     * @ORM\Column(name="message", type="text", nullable=false)
     */
    protected $message;

    /**
     * @var \DateTime
     * @ORM\Column(name="createdAt", type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @var \DateTime
     * @ORM\Column(name="modifiedAt", type="datetime", nullable=false)
     */
    protected $modifiedAt;

    /**
     * @var MessageUser
     * @ORM\ManyToOne(targetEntity="MessageUser", inversedBy="sentMessages")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     */
    protected $messageUser;

    /**
     * @var Channel
     * @ORM\ManyToOne(targetEntity="Channel", inversedBy="messages")
     * @ORM\JoinColumn(name="channel_id", referencedColumnName="channel_id")
     */
    protected $channel;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Channel", mappedBy="messageUsers")
     */
    protected $channels;

    public function __construct($message=null, $createdBy=null, $directedToUser='') {
        $this->messageUsers     = new ArrayCollection();
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
        return $this->message_id;
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
     * Set user
     *
     * @param \AppBundle\Entity\MessageUser $messageUser
     * @return Message
     */
    public function setUser(\AppBundle\Entity\MessageUser $messageUser = null)
    {
        $this->messageUser = $messageUser;

        return $this;
    }

    /**
     * Get messageUser
     *
     * @return \AppBundle\Entity\MessageUser
     */
    public function getUser()
    {
        return $this->messageUser;
    }

    /**
     * Set channel
     *
     * @param \AppBundle\Entity\Channel $channel
     * @return Channel
     */
    public function setChannel(\AppBundle\Entity\Channel $channel = null)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Get channel
     *
     * @return \AppBundle\Entity\Channel
     */
    public function getChannel()
    {
        return $this->channel;
    }
}

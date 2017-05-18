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
 * @ORM\Table(name="channel")
 */
class Channel {

    /**
     * @ORM\Id()
     * @ORM\Column(name="channel_id", type = "integer")
     * @ORM\GeneratedValue(strategy = "IDENTITY")
     * @var integer
     */
    protected $channel_id;

    /**
     * @var \string
     * @ORM\Column(name="channelName", type="string", nullable=false)
     */
    protected $channelName;

    /**
     * @var \boolean
     * @ORM\Column(name="singular", type="boolean", nullable=false, options={"default" : true})
     */
    protected $singular = true;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="MessageUser", inversedBy="channels")
     * @ORM\JoinTable(name="message_user_channels",
     *  joinColumns={
     *      @ORM\JoinColumn(name="channel_id", referencedColumnName="channel_id")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     *  }
     * )
     */
    protected $messageUsers;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Message", mappedBy="channel")
     */
    protected $messages;

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



    public function __construct() {
        
        $date = new \DateTime();
        $this->messageUsers     = new ArrayCollection();
        $this->messages     = new ArrayCollection();
        $this->createdAt = $date;
        $this->modifiedAt = $date;
        // $orm = $this->getDoctrine()->getManager();
    }

    /**
     *
     * @return int
     */
    public function getId() {
        return $this->channel_id;
    }
    
    /**
     *
     * @return string
     */
    public function getChannelName() {
        return $this->channelName;
    }

    public function setChannelName($channelName) {
        $this->channelName = $channelName;
    }
    
    /**
     *
     * @return boolean
     */
    public function getSingular() {
        return $this->singular;
    }

    public function setSingular($singular) {
        $this->singular = $singular;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Channel
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
     * @return Channel
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
     * Add messageUsers
     *
     * @param \AppBundle\Entity\MessageUser $messageUsers
     * @return Channel
     */
    public function addMessageUser(\AppBundle\Entity\MessageUser $messageUsers)
    {
        $this->messageUsers[] = $messageUsers;

        return $this;
    }

    /**
     * Remove messageUsers
     *
     * @param \AppBundle\Entity\MessageUser $messageUsers
     */
    public function removeMessageUser(\AppBundle\Entity\MessageUser $messageUsers)
    {
        $this->messageUsers->removeElement($messageUsers);
    }

    /**
     * Get messageUsers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMessageUsers()
    {
        return $this->messageUsers;
    }

}

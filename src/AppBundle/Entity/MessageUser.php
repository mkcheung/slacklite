<?php
/**
 * Created by PhpStorm.
 * User: marscheung
 * Date: 4/8/17
 * Time: 10:01 PM
 */


namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\DateTimeType;
/**
 * @ORM\Entity
 * @ORM\Table(name="message_user")
 */
class MessageUser extends BaseUser{

    /**
     * @ORM\Id()
     * @ORM\Column(name="user_id", type = "integer")
     * @ORM\GeneratedValue(strategy = "IDENTITY")
     * @var integer
     */
    protected $id;


    /**
     * @var \string
     * @ORM\Column(name="firstName", type="string", nullable=false)
     */
    protected $firstName;
    /**
     * @var \string
     * @ORM\Column(name="lastName", type="string", nullable=false)
     */
    protected $lastName;

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
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Message", mappedBy="user")
     */
    protected $sentMessages;

    public function __construct() {
        
        parent::__construct();
        $date = new \DateTime();
        // $this->sentMessages     = new ArrayCollection();
        $this->createdAt = $date;
        $this->modifiedAt = $date;
        // $orm = $this->getDoctrine()->getManager();
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
     * @return string
     */
    public function getFirstName() {
        return $this->firstName;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    
    /**
     *
     * @return string
     */
    public function getLastName() {
        return $this->lastName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
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

}

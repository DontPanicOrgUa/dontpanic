<?php
namespace WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="games", uniqueConstraints={@ORM\UniqueConstraint(name="unique_room_datetime", columns={"room_id", "datetime"})})
 * @ORM\Entity(repositoryClass="WebBundle\Repository\GameRepository")
 */
class Game
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $datetime;

    /**
     * Storing price, currency, players, discount
     * @ORM\Column(type="string", nullable=false)
     */
    private $bookingData;

    /**
     * @ORM\Column(type="boolean", options={"default"="0"})
     */
    private $is_paid;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $result;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $photo;

    /**
     * @ORM\ManyToOne(targetEntity="WebBundle\Entity\Room", inversedBy="games")
     */
    private $room;

    /**
     * @ORM\ManyToOne(targetEntity="WebBundle\Entity\Customer", inversedBy="games")
     */
    private $customer;

    /**
     * @ORM\OneToMany(targetEntity="WebBundle\Entity\Bill", mappedBy="game")
     */
    private $bills;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $bookedBy;

    /**
     * @ORM\OneToMany(targetEntity="WebBundle\Entity\Reward", mappedBy="game", cascade={"remove"})
     */
    private $rewards;

    /**
     * @var \DateTime $createdAt
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdAt;

    public function __construct()
    {
        $this->bills = new ArrayCollection();
        $this->is_paid = false;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * @param mixed $datetime
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;
    }

    /**
     * @return mixed
     */
    public function getBookingData()
    {
        return json_decode($this->bookingData, 1);
    }

    /**
     * @param mixed $bookingData
     */
    public function setBookingData($bookingData)
    {
        $this->bookingData = $bookingData;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @return mixed
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param mixed $photo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    /**
     * @return mixed
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * @param mixed $room
     */
    public function setRoom($room)
    {
        $this->room = $room;
    }

    /**
     * @return mixed
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param mixed $customer
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
    }

    /**
     * @return mixed
     */
    public function getBills()
    {
        return $this->bills;
    }

    /**
     * @param mixed $bills
     */
    public function setBills($bills)
    {
        $this->bills = $bills;
    }

    /**
     * @return mixed
     */
    public function getBookedBy()
    {
        return $this->bookedBy;
    }

    /**
     * @param mixed $bookedBy
     */
    public function setBookedBy($bookedBy)
    {
        $this->bookedBy = $bookedBy;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return mixed
     */
    public function getIsPaid()
    {
        return $this->is_paid;
    }

    /**
     * @param mixed $is_paid
     */
    public function setIsPaid($is_paid)
    {
        $this->is_paid = $is_paid;
    }

    /**
     * @return mixed
     */
    public function getRewards()
    {
        return $this->rewards;
    }

    /**
     * @param mixed $rewards
     */
    public function setRewards($rewards)
    {
        $this->rewards = $rewards;
    }

}
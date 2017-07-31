<?php
namespace WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\NotBlank()
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $datetime;

    /**
     * Storing price, currency, players, discount
     * @Assert\NotBlank()
     * @ORM\Column(type="string", nullable=false)
     */
    private $bookingData;


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
     * @ORM\Column(type="integer", nullable=false)
     */
    private $bookedBy;

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
        return $this->bookingData;
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

}
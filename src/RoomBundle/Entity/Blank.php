<?php

declare(strict_types=1);

namespace RoomBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="RoomBundle\Repository\BlankRepository")
 * @ORM\Table(name="blanks")
 */
class Blank
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="time")
     * @Assert\NotBlank()
     */
    private $time;

    /**
     * @ORM\ManyToOne(targetEntity="RoomBundle\Entity\Room", inversedBy="blanks")
     * @JoinColumn(name="room_id", referencedColumnName="id")
     */
    private $room;

    /**
     * @ORM\OneToMany(targetEntity="RoomBundle\Entity\Price", mappedBy="blank", cascade={"remove"})
     * @ORM\OrderBy({"price" = "ASC"})
     */
    private $prices;

    public function __construct()
    {
        $this->prices = new ArrayCollection();
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
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return Room
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * @param Room $room
     */
    public function setRoom(Room $room)
    {
        $this->room = $room;
    }

    /**
     * @return ArrayCollection|Price[]
     */
    public function getPrices()
    {
        return $this->prices;
    }

    /**
     * @param $dayOfWeek
     * @return ArrayCollection|\Doctrine\Common\Collections\Collection
     */
    public function getPricesByDayOfWeek($dayOfWeek)
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('dayOfWeek', $dayOfWeek))
            ->orderBy(['price' => 'ASC']);
        return $this->prices->matching($criteria);
    }

    /**
     * @param Price $prices
     */
    public function setPrices(Price $prices)
    {
        $this->prices = $prices;
    }

    public function __toString()
    {
        return $this->getTime()->format('H:i');
    }

}
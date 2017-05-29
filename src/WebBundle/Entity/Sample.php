<?php

namespace WebBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sample")
 */
class Sample
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="time")
     */
    private $time;

    /**
     * @ORM\Column(type="string")
     */
    private $dayOfWeek;

    /**
     * @ORM\Column(type="string")
     */
    private $playersPrice;

    /**
     * @ORM\ManyToOne(targetEntity="WebBundle\Entity\Room")
     */
    private $room;

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
    public function setRoom(Room $room)
    {
        $this->room = $room;
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
     * @return mixed
     */
    public function getDayOfWeek()
    {
        return $this->dayOfWeek;
    }

    /**
     * @param mixed $dayOfWeek
     */
    public function setDayOfWeek($dayOfWeek)
    {
        $this->dayOfWeek = $dayOfWeek;
    }

    /**
     * @return mixed
     */
    public function getPlayersPrice()
    {
        return $this->playersPrice;
    }

    /**
     * @param mixed $playersPrice
     */
    public function setPlayersPrice($playersPrice)
    {
        $this->playersPrice = $playersPrice;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

}
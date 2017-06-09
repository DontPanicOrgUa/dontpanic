<?php

namespace WebBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="WebBundle\Repository\BlankRepository")
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
     * @ORM\ManyToOne(targetEntity="WebBundle\Entity\Room", inversedBy="blanks")
     */
    private $room;

    /**
     * @ORM\OneToMany(targetEntity="WebBundle\Entity\Price", mappedBy="blank")
     * @ORM\OrderBy({"price" = "DESC"})
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
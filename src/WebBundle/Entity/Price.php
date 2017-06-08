<?php

namespace WebBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="WebBundle\Repository\PriceRepository")
 * @ORM\Table(name="prices")
 */
class Price
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Regex("/[0-9-]/", message="Only numbers and '-' are available.")
     */
    private $players;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Type(type="integer")
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="WebBundle\Entity\Blank", inversedBy="prices")
     * @Assert\NotBlank(message="Please chose the time")
     */
    private $blank;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\NotBlank(message="Please chose the day")
     */
    private $dayOfWeek;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPlayers()
    {
        return $this->players;
    }

    /**
     * @param string $players
     */
    public function setPlayers($players)
    {
        $this->players = $players;
    }

    /**
     * @return integer
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param integer $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return Blank
     */
    public function getBlank()
    {
        return $this->blank;
    }

    /**
     * @param Blank $blank
     */
    public function setBlank($blank)
    {
        $this->blank = $blank;
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

}
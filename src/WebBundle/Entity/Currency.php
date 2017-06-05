<?php

namespace WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="currencies")
 * @ORM\Entity(repositoryClass="WebBundle\Repository\CurrencyRepository")
 * @UniqueEntity(fields={"currency"})
 */
class Currency
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $currency;

    /**
     * @ORM\OneToMany(targetEntity="WebBundle\Entity\Room", mappedBy="currency")
     */
    private $rooms;

    function __construct()
    {
        $this->rooms = new ArrayCollection();
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return ArrayCollection|Room[]
     */
    public function getRooms()
    {
        return $this->rooms;
    }

    public function __toString()
    {
        return $this->getCurrency();
    }

}


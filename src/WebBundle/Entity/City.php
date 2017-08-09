<?php

namespace WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Intl\Locale;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="cities")
 * @ORM\Entity(repositoryClass="WebBundle\Repository\CityRepository")
 * @UniqueEntity(fields={"nameRu"})
 * @UniqueEntity(fields={"nameEn"})
 * @UniqueEntity(fields={"nameDe"})
 */
class City
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
    private $nameRu;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
    private $nameEn;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
    private $nameDe;

    /**
     * @ORM\OneToMany(targetEntity="WebBundle\Entity\Room", mappedBy="city")
     */
    private $rooms;

    function __construct()
    {
        $this->rooms = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $locale
     * @return string
     */
    public function getName($locale)
    {
        $locale = Locale::getDefault();
        dump($locale);
        $nameLocale = 'name' . ucfirst($locale);
        return $this->$nameLocale;
    }

    /**
     * @param string $name
     * @param null $locale
     */
    public function setName($name, $locale)
    {
        $nameLocale = 'name' . ucfirst($locale);
        $this->$nameLocale = $name;
    }

    /**
     * @return mixed
     */
    public function getNameRu()
    {
        return $this->nameRu;
    }

    /**
     * @param mixed $nameRu
     */
    public function setNameRu($nameRu)
    {
        $this->nameRu = $nameRu;
    }

    /**
     * @return mixed
     */
    public function getNameEn()
    {
        return $this->nameEn;
    }

    /**
     * @param mixed $nameEn
     */
    public function setNameEn($nameEn)
    {
        $this->nameEn = $nameEn;
    }

    /**
     * @return mixed
     */
    public function getNameDe()
    {
        return $this->nameDe;
    }

    /**
     * @param mixed $nameDe
     */
    public function setNameDe($nameDe)
    {
        $this->nameDe = $nameDe;
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
        return $this->getNameEn();
    }

}


<?php

namespace WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="genres")
 * @ORM\Entity(repositoryClass="WebBundle\Repository\GenreRepository")
 * @UniqueEntity(fields={"nameRu"})
 * @UniqueEntity(fields={"nameEn"})
 * @UniqueEntity(fields={"nameDe"})
 */
class Genre
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
        $nameLocale = 'name' . ucfirst($locale);
        return $this->$nameLocale;
    }

    /**
     * @param string $name
     * @param string $locale
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

}


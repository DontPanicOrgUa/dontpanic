<?php

namespace WebBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Table(name="shares")
 * @ORM\Entity(repositoryClass="WebBundle\Repository\ShareRepository")
 */
class Share
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\File(
     *     maxSize = "2M",
     *     mimeTypes={ "image/jpeg" }
     * )
     */
    private $imgRu;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\File(
     *     maxSize = "2M",
     *     mimeTypes={ "image/jpeg" }
     * )
     */
    private $imgEn;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\File(
     *     maxSize = "2M",
     *     mimeTypes={ "image/jpeg" }
     * )
     */
    private $imgDe;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="text")
     */
    private $descriptionRu;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="text")
     */
    private $descriptionEn;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="text")
     */
    private $descriptionDe;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $locale
     * @return string
     */
    public function getImg($locale)
    {
        $imgLocale = 'img' . ucfirst($locale);
        return $this->$imgLocale;
    }

    /**
     * @param string $img
     * @param null $locale
     */
    public function setImg($img, $locale)
    {
        $imgLocale = 'description' . ucfirst($locale);
        $this->$imgLocale = $img;
    }

    /**
     * @return mixed
     */
    public function getImgRu()
    {
        return $this->imgRu;
    }

    /**
     * @param mixed $imgRu
     */
    public function setImgRu($imgRu)
    {
        $this->imgRu = $imgRu;
    }

    /**
     * @return mixed
     */
    public function getImgEn()
    {
        return $this->imgEn;
    }

    /**
     * @param mixed $imgEn
     */
    public function setImgEn($imgEn)
    {
        $this->imgEn = $imgEn;
    }

    /**
     * @return mixed
     */
    public function getImgDe()
    {
        return $this->imgDe;
    }

    /**
     * @param mixed $imgDe
     */
    public function setImgDe($imgDe)
    {
        $this->imgDe = $imgDe;
    }

    /**
     * @param string $locale
     * @return string
     */
    public function getDescription($locale)
    {
        $descriptionLocale = 'description' . ucfirst($locale);
        return $this->$descriptionLocale;
    }

    /**
     * @param string $description
     * @param null $locale
     */
    public function setDescription($description, $locale)
    {
        $descriptionLocale = 'description' . ucfirst($locale);
        $this->$descriptionLocale = $description;
    }

    /**
     * @return mixed
     */
    public function getDescriptionRu()
    {
        return $this->descriptionRu;
    }

    /**
     * @param mixed $descriptionRu
     */
    public function setDescriptionRu($descriptionRu)
    {
        $this->descriptionRu = $descriptionRu;
    }

    /**
     * @return mixed
     */
    public function getDescriptionEn()
    {
        return $this->descriptionEn;
    }

    /**
     * @param mixed $descriptionEn
     */
    public function setDescriptionEn($descriptionEn)
    {
        $this->descriptionEn = $descriptionEn;
    }

    /**
     * @return mixed
     */
    public function getDescriptionDe()
    {
        return $this->descriptionDe;
    }

    /**
     * @param mixed $descriptionDe
     */
    public function setDescriptionDe($descriptionDe)
    {
        $this->descriptionDe = $descriptionDe;
    }

}


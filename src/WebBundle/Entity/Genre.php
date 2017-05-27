<?php

namespace WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="genres")
 * @ORM\Entity(repositoryClass="WebBundle\Repository\GenreRepository")
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
    private $titleRu;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
    private $titleEn;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
    private $titleDe;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $locale
     * @return string
     */
    public function getTitle($locale = null)
    {
        $locale = !empty($locale) ? $locale : \Locale::getDefault();
        $titleLocale = 'title' . ucfirst($locale);
        return $this->$titleLocale;
    }

    /**
     * @param string $title
     * @param null $locale
     */
    public function setTitle($title, $locale = null)
    {
        $locale = !empty($locale) ? $locale : \Locale::getDefault();
        $titleLocale = 'title' . ucfirst($locale);
        $this->$titleLocale = $title;
    }

    /**
     * @return mixed
     */
    public function getTitleRu()
    {
        return $this->titleRu;
    }

    /**
     * @param mixed $titleRu
     */
    public function setTitleRu($titleRu)
    {
        $this->titleRu = $titleRu;
    }

    /**
     * @return mixed
     */
    public function getTitleEn()
    {
        return $this->titleEn;
    }

    /**
     * @param mixed $titleEn
     */
    public function setTitleEn($titleEn)
    {
        $this->titleEn = $titleEn;
    }

    /**
     * @return mixed
     */
    public function getTitleDe()
    {
        return $this->titleDe;
    }

    /**
     * @param mixed $titleDe
     */
    public function setTitleDe($titleDe)
    {
        $this->titleDe = $titleDe;
    }

}


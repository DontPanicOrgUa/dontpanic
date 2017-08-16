<?php

namespace WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="pages")
 * @ORM\Entity(repositoryClass="WebBundle\Repository\PageRepository")
 * @UniqueEntity(fields={"titleRu"})
 * @UniqueEntity(fields={"titleEn"})
 * @UniqueEntity(fields={"titleDe"})
 */
class Page
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

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
    private $contentRu;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
    private $contentEn;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
    private $contentDe;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isInMenu = false;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Gedmo\Slug(fields={"titleEn"})
     */
    private $slug;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $locale
     * @return string
     */
    public function getTitle($locale)
    {
        $titleLocale = 'title' . ucfirst($locale);
        return $this->$titleLocale;
    }

    /**
     * @param string $title
     * @param null $locale
     */
    public function setTitle($title, $locale)
    {
        $titleLocale = 'title' . ucfirst($locale);
        $this->$titleLocale = $title;
    }

    /**
     * @param string $locale
     * @return string
     */
    public function getContent($locale)
    {
        $contentLocale = 'content' . ucfirst($locale);
        return $this->$contentLocale;
    }

    /**
     * @param string $content
     * @param null $locale
     */
    public function setContent($content, $locale)
    {
        $contentLocale = 'content' . ucfirst($locale);
        $this->$contentLocale = $content;
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

    /**
     * @return mixed
     */
    public function getContentRu()
    {
        return $this->contentRu;
    }

    /**
     * @param mixed $contentRu
     */
    public function setContentRu($contentRu)
    {
        $this->contentRu = $contentRu;
    }

    /**
     * @return mixed
     */
    public function getContentEn()
    {
        return $this->contentEn;
    }

    /**
     * @param mixed $contentEn
     */
    public function setContentEn($contentEn)
    {
        $this->contentEn = $contentEn;
    }

    /**
     * @return mixed
     */
    public function getContentDe()
    {
        return $this->contentDe;
    }

    /**
     * @param mixed $contentDe
     */
    public function setContentDe($contentDe)
    {
        $this->contentDe = $contentDe;
    }

    /**
     * @return mixed
     */
    public function getIsInMenu()
    {
        return $this->isInMenu;
    }

    /**
     * @param mixed $isInMenu
     */
    public function setIsInMenu($isInMenu)
    {
        $this->isInMenu = $isInMenu;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

}


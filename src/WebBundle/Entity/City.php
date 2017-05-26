<?php

namespace WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="cities")
 * @ORM\Entity(repositoryClass="WebBundle\Repository\CityRepository")
 * @Gedmo\TranslationEntity(class="WebBundle\Entity\CityTranslation")
 */
class City
{

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @ORM\OneToMany(
     *   targetEntity="WebBundle\Entity\CityTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    private $translations;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function setName($title)
    {
        $this->title = $title;
    }

    public function getName()
    {
        return $this->title;
    }

    public function getTranslations()
    {
        return $this->translations;
    }

    public function addTranslation(CityTranslation $t)
    {
        if (!$this->translations->contains($t)) {
            $this->translations[] = $t;
            $t->setObject($this);
        }
    }

}


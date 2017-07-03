<?php

namespace WebBundle\Entity;

use AdminBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="rooms")
 * @ORM\Entity(repositoryClass="WebBundle\Repository\RoomRepository")
 * @UniqueEntity(fields={"titleRu"})
 * @UniqueEntity(fields={"titleEn"})
 * @UniqueEntity(fields={"titleDe"})
 */
class Room
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
     * @ORM\Column(type="string", nullable=true)
     * @Assert\File(mimeTypes={ "image/png" })
     */
    private $logo;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\File(mimeTypes={ "image/jpeg" })
     */
    private $background;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
    private $coordinates;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
    private $addressRu;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
    private $addressEn;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
    private $addressDe;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
    private $phone;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
    private $email;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = 1,
     *      max = 10,
     *      minMessage = "Difficulty must be not less than 1.",
     *      maxMessage = "Difficulty must be not more than 10."
     * )
     */
    private $difficulty = 5;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = 1,
     *      minMessage = "Minimum time must be not less than 1 minute.",
     * )
     */
    private $timeMax = 60;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="integer")
     */
    private $playersMin = 2;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="integer")
     */
    private $playersMax = 4;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = 3,
     *      max = 90,
     *      minMessage = "Min allowed age 3 years.",
     *      maxMessage = "Min allowed age 90 years."
     * )
     */
    private $ageMin = 14;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled = true;

    /**
     * @ORM\OneToMany(targetEntity="WebBundle\Entity\Blank", mappedBy="room")
     * @ORM\OrderBy({"time" = "ASC"})
     */
    private $blanks;

    /**
     * @ORM\OneToMany(targetEntity="WebBundle\Entity\Game", mappedBy="room")
     */
    private $games;

    /**
     * @ORM\ManyToOne(targetEntity="WebBundle\Entity\City", inversedBy="rooms")
     * @ORM\JoinColumn(nullable=false)
     */
    private $city;

    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="WebBundle\Entity\Currency", inversedBy="rooms")
     * @ORM\JoinColumn(nullable=false)
     */
    private $currency;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Gedmo\Slug(fields={"titleEn"})
     */
    private $slug;

    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="WebBundle\Entity\TimeZone")
     * @ORM\JoinColumn(nullable=false)
     */
    private $timezone;

    /**
     * @ORM\ManyToMany(targetEntity="AdminBundle\Entity\User")
     */
    private $roomManagers;

    /**
     * @var \DateTime $createdAt
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdAt;

    /**
     * @var \DateTime $updatedAt
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    public function __construct()
    {
        $this->blanks = new ArrayCollection();
        $this->roomManagers = new ArrayCollection();
    }

    /**
     * @return integer
     */
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

    /**
     * @param string $locale
     * @return string
     */
    public function getDescription($locale = null)
    {
        $locale = !empty($locale) ? $locale : \Locale::getDefault();
        $descriptionLocale = 'description' . ucfirst($locale);
        return $this->$descriptionLocale;
    }

    /**
     * @param string $description
     * @param null $locale
     */
    public function setDescription($description, $locale = null)
    {
        $locale = !empty($locale) ? $locale : \Locale::getDefault();
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

    /**
     * @return mixed
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param mixed $logo
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
    }

    /**
     * @return mixed
     */
    public function getBackground()
    {
        return $this->background;
    }

    /**
     * @param mixed $background
     */
    public function setBackground($background)
    {
        $this->background = $background;
    }

    /**
     * @return mixed
     */
    public function getCoordinates()
    {
        return $this->coordinates;
    }

    /**
     * @param mixed $coordinates
     */
    public function setCoordinates($coordinates)
    {
        $this->coordinates = $coordinates;
    }

    /**
     * @param string $locale
     * @return string
     */
    public function getAddress($locale = null)
    {
        $locale = !empty($locale) ? $locale : \Locale::getDefault();
        $addressLocale = 'address' . ucfirst($locale);
        return $this->$addressLocale;
    }

    /**
     * @param string $address
     * @param null $locale
     */
    public function setAddress($address, $locale = null)
    {
        $locale = !empty($locale) ? $locale : \Locale::getDefault();
        $addressLocale = 'description' . ucfirst($locale);
        $this->$addressLocale = $address;
    }

    /**
     * @return mixed
     */
    public function getAddressRu()
    {
        return $this->addressRu;
    }

    /**
     * @param mixed $addressRu
     */
    public function setAddressRu($addressRu)
    {
        $this->addressRu = $addressRu;
    }

    /**
     * @return mixed
     */
    public function getAddressEn()
    {
        return $this->addressEn;
    }

    /**
     * @param mixed $addressEn
     */
    public function setAddressEn($addressEn)
    {
        $this->addressEn = $addressEn;
    }

    /**
     * @return mixed
     */
    public function getAddressDe()
    {
        return $this->addressDe;
    }

    /**
     * @param mixed $addressDe
     */
    public function setAddressDe($addressDe)
    {
        $this->addressDe = $addressDe;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getDifficulty()
    {
        return $this->difficulty;
    }

    /**
     * @param mixed $difficulty
     */
    public function setDifficulty($difficulty)
    {
        $this->difficulty = $difficulty;
    }

    /**
     * @return mixed
     */
    public function getTimeMax()
    {
        return $this->timeMax;
    }

    /**
     * @param mixed $timeMax
     */
    public function setTimeMax($timeMax)
    {
        $this->timeMax = $timeMax;
    }

    /**
     * @return mixed
     */
    public function getPlayersMin()
    {
        return $this->playersMin;
    }

    /**
     * @param mixed $playersMin
     */
    public function setPlayersMin($playersMin)
    {
        $this->playersMin = $playersMin;
    }

    /**
     * @return mixed
     */
    public function getPlayersMax()
    {
        return $this->playersMax;
    }

    /**
     * @param mixed $playersMax
     */
    public function setPlayersMax($playersMax)
    {
        $this->playersMax = $playersMax;
    }

    /**
     * @return mixed
     */
    public function getAgeMin()
    {
        return $this->ageMin;
    }

    /**
     * @param mixed $ageMin
     */
    public function setAgeMin($ageMin)
    {
        $this->ageMin = $ageMin;
    }

    /**
     * @return mixed
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param mixed $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return ArrayCollection|Blank[]
     */
    public function getBlanks()
    {
        return $this->blanks;
    }

    /**
     * @param Blank $blanks
     */
    public function setBlanks(Blank $blanks)
    {
        $this->blanks = $blanks;
    }

    /**
     * @return City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param City $city
     */
    public function setCity(City $city)
    {
        $this->city = $city;
    }

    /**
     * @return Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param Currency $currency
     */
    public function setCurrency(Currency $currency)
    {
        $this->currency = $currency;
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

    /**
     * @return string
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * @param string $timezone
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
    }

    public function addRoomManager(User $user)
    {
        if ($this->roomManagers->contains($user)) {
            return;
        }
        $this->roomManagers[] = $user;
    }

    public function removeRoomManager(User $user)
    {
        $this->roomManagers->removeElement($user);
    }

    /**
     * @return ArrayCollection|User[]
     */
    public function getRoomManagers()
    {
        return $this->roomManagers;
    }

    /**
     * @return mixed
     */
    public function getGames()
    {
        return $this->games;
    }

    /**
     * @param mixed $games
     */
    public function setGames($games)
    {
        $this->games = $games;
    }


}


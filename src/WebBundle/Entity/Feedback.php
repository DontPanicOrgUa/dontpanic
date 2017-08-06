<?php

namespace WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="feedbacks")
 * @ORM\Entity(repositoryClass="WebBundle\Repository\FeedbackRepository")
 */
class Feedback
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
    private $name;

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
     * @ORM\Column(type="text")
     */
    private $comment;

    /**
     * @ORM\Column(type="integer")
     */
    private $atmospheric;

    /**
     * @ORM\Column(type="integer")
     */
    private $story;

    /**
     * @ORM\Column(type="integer")
     */
    private $service;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="integer")
     */
    private $time;

    /**
     * @ORM\Column(type="boolean", options={"default":0})
     */
    private $isActive;

    /**
     * @ORM\ManyToOne(targetEntity="WebBundle\Entity\Room")
     */
    private $rooms;

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

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return mixed
     */
    public function getAtmospheric()
    {
        return $this->atmospheric;
    }

    /**
     * @param mixed $atmospheric
     */
    public function setAtmospheric($atmospheric)
    {
        $this->atmospheric = $atmospheric;
    }

    /**
     * @return mixed
     */
    public function getStory()
    {
        return $this->story;
    }

    /**
     * @param mixed $story
     */
    public function setStory($story)
    {
        $this->story = $story;
    }

    /**
     * @return mixed
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param mixed $service
     */
    public function setService($service)
    {
        $this->service = $service;
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
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @return mixed
     */
    public function getRooms()
    {
        return $this->rooms;
    }

    /**
     * @param mixed $rooms
     */
    public function setRooms($rooms)
    {
        $this->rooms = $rooms;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

}


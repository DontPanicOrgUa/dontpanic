<?php

namespace WebBundle\Entity;


use Locale;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="notifications")
 * @ORM\Entity(repositoryClass="WebBundle\Repository\NotificationRepository")
 */
class Notification
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $type;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $event;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $recipient;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $titleRu;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $titleEn;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $titleDe;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $messageRu;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $messageEn;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $messageDe;

    /**
     * @param string $locale
     * @return string
     */
    public function getMessage($locale = null)
    {
        $locale = !empty($locale) ? $locale : Locale::getDefault();
        $messageLocale = 'message' . ucfirst($locale);
        return $this->$messageLocale;
    }

    /**
     * @param string $message
     * @param null $locale
     */
    public function setMessage($message, $locale = null)
    {
        $locale = !empty($locale) ? $locale : Locale::getDefault();
        $messageLocale = 'message' . ucfirst($locale);
        $this->$messageLocale = $message;
    }

    /**
     * @param string $locale
     * @return string
     */
    public function getTitle($locale = null)
    {
        $locale = !empty($locale) ? $locale : Locale::getDefault();
        $titleLocale = 'title' . ucfirst($locale);
        return $this->$titleLocale;
    }

    /**
     * @param $title
     * @param null $locale
     */
    public function setTitle($title, $locale = null)
    {
        $locale = !empty($locale) ? $locale : Locale::getDefault();
        $titleLocale = 'title' . ucfirst($locale);
        $this->$titleLocale = $title;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getMessageRu()
    {
        return $this->messageRu;
    }

    /**
     * @param mixed $messageRu
     */
    public function setMessageRu($messageRu)
    {
        $this->messageRu = $messageRu;
    }

    /**
     * @return mixed
     */
    public function getMessageEn()
    {
        return $this->messageEn;
    }

    /**
     * @param mixed $messageEn
     */
    public function setMessageEn($messageEn)
    {
        $this->messageEn = $messageEn;
    }

    /**
     * @return mixed
     */
    public function getMessageDe()
    {
        return $this->messageDe;
    }

    /**
     * @param mixed $messageDe
     */
    public function setMessageDe($messageDe)
    {
        $this->messageDe = $messageDe;
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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param mixed $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     * @return mixed
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param mixed $recipient
     */
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;
    }

}
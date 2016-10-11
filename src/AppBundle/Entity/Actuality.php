<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Actuality
 *
 * @ORM\Table(name="actuality")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ActualityRepository")
 */
class Actuality
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @Assert\NotBlank(message="Veuillez charger un document Word ou PDF.")
     * @Assert\File(mimeTypes={ "application/pdf, application/msword" })
     */
    private $attachment;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @Assert\NotBlank(message="Veuillez charger une image.")
     * @Assert\File(mimeTypes={ "image/*" })
     */
    private $thumbnail;

    /**
     * @var int
     *
     * @ORM\Column(name="target", type="integer")
     */
    private $target;

    /**
     * @var int
     *
     * @ORM\Column(name="target_id", type="integer", nullable=true)
     */
    private $targetId;


    public function __construct()
    {
        $this->date = new \DateTime();
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

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Actuality
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Actuality
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Actuality
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set attachment
     *
     * @param string $attachment
     *
     * @return Actuality
     */
    public function setAttachment($attachment)
    {
        $this->attachment = $attachment;

        return $this;
    }

    /**
     * Get attachment
     *
     * @return string
     */
    public function getAttachment()
    {
        return $this->attachment;
    }

    /**
     * Set thumbnail
     *
     * @param integer $thumbnail
     *
     * @return Actuality
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    /**
     * Get thumbnail
     *
     * @return string
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * Set target
     *
     * @param integer $target
     *
     * @return Actuality
     */
    public function setTarget($target)
    {
        $this->target = $target;

        return $this;
    }

    /**
     * Get target
     *
     * @return int
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Set targetId
     *
     * @param integer $targetId
     *
     * @return Actuality
     */
    public function setTargetId($targetId)
    {
        $this->targetId = $targetId;

        return $this;
    }

    /**
     * Get targetId
     *
     * @return int
     */
    public function getTargetId()
    {
        return $this->targetId;
    }
    /**
     * Set date
     *
     * @param datetime $date
     *
     * @return Actuality
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return datetime
     */
    public function getDate()
    {
        return $this->date;
    }
}


<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Attachment
 *
 * @ORM\Table(name="attachment")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AttachmentRepository")
 */
class Attachment
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
     * @ORM\Column(type="string", nullable=true)
     * @Assert\NotBlank(message="Veuillez charger un document Word, PDF, compressé ou vidéo.")
     * @Assert\File(mimeTypes={ "application/pdf, application/msword, video/*, application/x-rar-compressed ,application/zip" })
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Lesson",inversedBy="attachments")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $lesson;


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
     * Set name
     *
     * @param string $name
     *
     * @return Attachment
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Attachment
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
     * Set lesson
     *
     * @param string $lesson
     *
     * @return Attachment
     */
    public function setLesson($lesson)
    {
        $this->lesson = $lesson;

        return $this;
    }

    /**
     * Get lesson
     *
     * @return string
     */
    public function getLesson()
    {
        return $this->lesson;
    }
}


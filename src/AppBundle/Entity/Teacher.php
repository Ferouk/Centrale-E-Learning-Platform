<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Teacher
 *
 * @ORM\Table(name="teacher")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TeacherRepository")
 */
class Teacher
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
     * @ORM\Column(type="string", nullable=true)
     * @Assert\NotBlank(message="Veuillez charger un document Word ou PDF.")
     * @Assert\File(mimeTypes={ "application/pdf, application/msword" })
     */
    private $cv;

    /**
     * @var string
     *
     * @ORM\OneToOne(targetEntity="User", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    
    /**
     * @var string
     *
     * @ORM\OneToMany(targetEntity="Course", mappedBy="teacher", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $courses;

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
     * @return Teacher
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
     * Set cv
     *
     * @param string $cv
     *
     * @return Teacher
     */
    public function setCv($cv)
    {
        $this->cv = $cv;

        return $this;
    }

    /**
     * Get cv
     *
     * @return string
     */
    public function getCv()
    {
        return $this->cv;
    }

    /**
     * Set user
     *
     * @param string $user
     *
     * @return Teacher
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Teacher
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
     * Set courses
     *
     * @param string $courses
     *
     * @return Teacher
     */
    public function setCourses($courses)
    {
        $this->courses = $courses;

        return $this;
    }

    /**
     * Get courses
     *
     * @return string
     */
    public function getCourses()
    {
        return $this->courses;
    }
}


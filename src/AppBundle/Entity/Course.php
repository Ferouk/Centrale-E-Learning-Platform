<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Course
 *
 * @ORM\Table(name="course")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CourseRepository")
 */
class Course
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

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
     * @ORM\Column(name="semestre", type="integer")
     */
    private $semestre;

    /**
     * @var float
     *
     * @ORM\Column(name="coefficient", type="float")
     */
    private $coefficient;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Teacher",inversedBy="courses")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $teacher;
    
    /**
     * @var Groups
     *
     * @ORM\OneToMany(targetEntity="Chapter", mappedBy="course", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $chapters;

    /**
     * @var string
     *
     * @ORM\OneToMany(targetEntity="Result", mappedBy="course", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $results;

    /**
     * @var bool
     *
     * @ORM\Column(name="hasTP", type="boolean")
     */
    private $hasTP;

    /**
     * @var bool
     *
     * @ORM\Column(name="hasAN", type="boolean")
     */
    private $hasAN;

    /**
     * @var bool
     *
     * @ORM\Column(name="hasDS2", type="boolean")
     */
    private $hasDS2;

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
     * @return Course
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
     * Set slug
     *
     * @param string $slug
     *
     * @return Course
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set thumbnail
     *
     * @param string $thumbnail
     *
     * @return Course
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
     * Set semestre
     *
     * @param integer $semestre
     *
     * @return Course
     */
    public function setSemestre($semestre)
    {
        $this->semestre = $semestre;

        return $this;
    }

    /**
     * Get semestre
     *
     * @return int
     */
    public function getSemestre()
    {
        return $this->semestre;
    }

    /**
     * Set coefficient
     *
     * @param float $coefficient
     *
     * @return Course
     */
    public function setCoefficient($coefficient)
    {
        $this->coefficient = $coefficient;

        return $this;
    }

    /**
     * Get coefficient
     *
     * @return float
     */
    public function getCoefficient()
    {
        return $this->coefficient;
    }

    /**
     * Set teacher
     *
     * @param string $teacher
     *
     * @return Course
     */
    public function setTeacher($teacher)
    {
        $this->teacher = $teacher;

        return $this;
    }

    /**
     * Get teacher
     *
     * @return string
     */
    public function getTeacher()
    {
        return $this->teacher;
    }

    /**
     * Set chapters
     *
     * @param string $chapters
     *
     * @return Course
     */
    public function setChapters($chapters)
    {
        $this->chapters = $chapters;

        return $this;
    }

    /**
     * Get chapters
     *
     * @return string
     */
    public function getChapters()
    {
        return $this->chapters;
    }

    /**
     * Set results
     *
     * @param string $results
     *
     * @return Course
     */
    public function setResults($results)
    {
        $this->results = $results;

        return $this;
    }

    /**
     * Get results
     *
     * @return string
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * Set hasTP
     *
     * @param string $hasTP
     *
     * @return Course
     */
    public function setHasTP($hasTP)
    {
        $this->hasTP = $hasTP;

        return $this;
    }

    /**
     * Get hasTP
     *
     * @return string
     */
    public function getHasTP()
    {
        return $this->hasTP;
    }

    /**
     * Set hasDS2
     *
     * @param string $hasDS2
     *
     * @return Course
     */
    public function setHasDS2($hasDS2)
    {
        $this->hasDS2 = $hasDS2;

        return $this;
    }

    /**
     * Get hasDS2
     *
     * @return string
     */
    public function getHasDS2()
    {
        return $this->hasDS2;
    }

    /**
     * Set hasAN
     *
     * @param string $hasAN
     *
     * @return Course
     */
    public function setHasAN($hasAN)
    {
        $this->hasAN = $hasAN;

        return $this;
    }

    /**
     * Get hasAN
     *
     * @return string
     */
    public function getHasAN()
    {
        return $this->hasAN;
    }
}


<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Classe
 *
 * @ORM\Table(name="classe")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClasseRepository")
 */
class Classe
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
     * @ORM\Column(name="cycle", type="string", length=255)
     */
    private $cycle;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Department",inversedBy="classes")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $department;

    /**
     * @var int
     *
     * @ORM\Column(name="level", type="integer")
     */
    private $level;

    /**
     * @var string
     *
     * @ORM\Column(name="speciality", type="string", length=255, nullable=true)
     */
    private $speciality;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var bool
     *
     * @ORM\Column(name="hasGroups", type="boolean")
     */
    private $hasGroups;

    /**
     * @var Groups
     *
     * @ORM\OneToMany(targetEntity="Groups", mappedBy="classe", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $groups;

    /**
     * @var gmue
     *
     * @ORM\OneToMany(targetEntity="GroupModule", mappedBy="classe", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $GroupModules;

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
     * Set cycle
     *
     * @param string $cycle
     *
     * @return Classe
     */
    public function setCycle($cycle)
    {
        $this->cycle = $cycle;

        return $this;
    }

    /**
     * Get cycle
     *
     * @return string
     */
    public function getCycle()
    {
        return $this->cycle;
    }

    /**
     * Set department
     *
     * @param string $department
     *
     * @return Classe
     */
    public function setDepartment($department)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * Get department
     *
     * @return string
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * Set level
     *
     * @param integer $level
     *
     * @return Classe
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set speciality
     *
     * @param string $speciality
     *
     * @return Classe
     */
    public function setSpeciality($speciality)
    {
        $this->speciality = $speciality;

        return $this;
    }

    /**
     * Get speciality
     *
     * @return string
     */
    public function getSpeciality()
    {
        return $this->speciality;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Classe
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
     * Set hasGroups
     *
     * @param boolean $hasGroups
     *
     * @return Classe
     */
    public function setHasGroups($hasGroups)
    {
        $this->hasGroups = $hasGroups;

        return $this;
    }

    /**
     * Get hasGroups
     *
     * @return bool
     */
    public function getHasGroups()
    {
        return $this->hasGroups;
    }

    /**
     * @return Groups
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @param Groups $groups
     */
    public function setGroups($groups)
    {
        $this->groups = $groups;
    }

    /**
     * @return gmue
     */
    public function getGroupModules()
    {
        return $this->GroupModules;
    }

    /**
     * @param gmue $GroupModules
     */
    public function setGroupModules($GroupModules)
    {
        $this->GroupModules = $GroupModules;
    }


}


<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Module
 *
 * @ORM\Table(name="module")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ModuleRepository")
 */
class Module
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
     * @var int
     *
     * @ORM\Column(name="semester", type="integer")
     */
    private $semester;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var float
     *
     * @ORM\Column(name="C", type="float", nullable=true)
     */
    private $c;

    /**
     * @var float
     *
     * @ORM\Column(name="TD", type="float", nullable=true)
     */
    private $tD;

    /**
     * @var float
     *
     * @ORM\Column(name="TP", type="float", nullable=true)
     */
    private $tP;

    /**
     * @var float
     *
     * @ORM\Column(name="coefficient", type="float")
     */
    private $coefficient;


    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="GroupModule",inversedBy="modules")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $GroupModule;


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
     * @return Module
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
     * Set semester
     *
     * @param integer $semester
     *
     * @return Module
     */
    public function setSemester($semester)
    {
        $this->semester = $semester;

        return $this;
    }

    /**
     * Get semester
     *
     * @return int
     */
    public function getSemester()
    {
        return $this->semester;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Module
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
     * Set c
     *
     * @param float $c
     *
     * @return Module
     */
    public function setC($c)
    {
        $this->c = $c;

        return $this;
    }

    /**
     * Get c
     *
     * @return float
     */
    public function getC()
    {
        return $this->c;
    }

    /**
     * Set tD
     *
     * @param float $tD
     *
     * @return Module
     */
    public function setTD($tD)
    {
        $this->tD = $tD;

        return $this;
    }

    /**
     * Get tD
     *
     * @return float
     */
    public function getTD()
    {
        return $this->tD;
    }

    /**
     * Set tP
     *
     * @param float $tP
     *
     * @return Module
     */
    public function setTP($tP)
    {
        $this->tP = $tP;

        return $this;
    }

    /**
     * Get tP
     *
     * @return float
     */
    public function getTP()
    {
        return $this->tP;
    }

    /**
     * Set coefficient
     *
     * @param float $coefficient
     *
     * @return Module
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
     * @return string
     */
    public function getGroupModule()
    {
        return $this->GroupModule;
    }

    /**
     * @param string $GroupModule
     */
    public function setGroupModule($GroupModule)
    {
        $this->GroupModule = $GroupModule;
    }


}


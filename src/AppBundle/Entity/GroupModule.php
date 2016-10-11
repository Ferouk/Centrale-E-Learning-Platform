<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GroupModule
 *
 * @ORM\Table(name="group_module")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GroupModuleRepository")
 */
class GroupModule
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
     * @ORM\ManyToOne(targetEntity="Classe",inversedBy="GroupModules")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $classe;

    /**
     * @var float
     *
     * @ORM\Column(name="coefficient", type="float")
     */
    private $coefficient;

    /**
     * @var int
     *
     * @ORM\Column(name="number", type="integer")
     */
    private $number;

    /**
     * @var Groups
     *
     * @ORM\OneToMany(targetEntity="Module", mappedBy="GroupModule", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $modules;


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
     * Set classe
     *
     * @param string $classe
     *
     * @return GroupModule
     */
    public function setClasse($classe)
    {
        $this->classe = $classe;

        return $this;
    }

    /**
     * Get classe
     *
     * @return string
     */
    public function getClasse()
    {
        return $this->classe;
    }

    /**
     * Set coefficient
     *
     * @param float $coefficient
     *
     * @return GroupModule
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
     * Set modules
     *
     * @param string $modules
     *
     * @return GroupModule
     */
    public function setModules($modules)
    {
        $this->modules = $modules;

        return $this;
    }

    /**
     * Get modules
     *
     * @return string
     */
    public function getModules()
    {
        return $this->modules;
    }

    /**
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param int $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }




}


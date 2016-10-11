<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Privilege
 *
 * @ORM\Table(name="privilege")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PrivilegeRepository")
 */
class Privilege
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
     * @var int
     *
     * @ORM\Column(name="user", type="integer")
     */
    private $user;

    /**
     * @var int
     *
     * @ORM\Column(name="site", type="integer")
     */
    private $site;

    /**
     * @var int
     *
     * @ORM\Column(name="classe", type="integer")
     */
    private $classe;

    /**
     * @var int
     *
     * @ORM\Column(name="department", type="integer")
     */
    private $department;

    /**
     * @var int
     *
     * @ORM\Column(name="curriculum", type="integer")
     */
    private $curriculum;

    /**
     * @var int
     *
     * @ORM\Column(name="post", type="integer")
     */
    private $post;

    /**
     * @var int
     *
     * @ORM\Column(name="config", type="integer")
     */
    private $config;

    /**
     * @var int
     *
     * @ORM\Column(name="admin", type="integer")
     */
    private $admin;

    /**
     * @var int
     *
     * @ORM\Column(name="results", type="integer")
     */
    private $results;

    /**
     * @var int
     *
     * @ORM\Column(name="course", type="integer")
     */
    private $course;

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
     * @ORM\OneToMany(targetEntity="User", mappedBy="privilege", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $users;


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
     * Get user
     *
     * @return int
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param integer $user
     *
     * @return Privilege
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get classe
     *
     * @return int
     */
    public function getClasse()
    {
        return $this->classe;
    }

    /**
     * Set classe
     *
     * @param integer $classe
     *
     * @return Privilege
     */
    public function setClasse($classe)
    {
        $this->classe = $classe;

        return $this;
    }

    /**
     * Get department
     *
     * @return int
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * Set department
     *
     * @param integer $department
     *
     * @return Privilege
     */
    public function setDepartment($department)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * Get curriculum
     *
     * @return int
     */
    public function getCurriculum()
    {
        return $this->curriculum;
    }

    /**
     * Set curriculum
     *
     * @param integer $curriculum
     *
     * @return Privilege
     */
    public function setCurriculum($curriculum)
    {
        $this->curriculum = $curriculum;

        return $this;
    }

    /**
     * Get post
     *
     * @return int
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set post
     *
     * @param integer $post
     *
     * @return Privilege
     */
    public function setPost($post)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get config
     *
     * @return int
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Set config
     *
     * @param integer $config
     *
     * @return Privilege
     */
    public function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Get admin
     *
     * @return int
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Set admin
     *
     * @param integer $admin
     *
     * @return Privilege
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * Get results
     *
     * @return int
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * Set results
     *
     * @param integer $results
     *
     * @return Privilege
     */
    public function setResults($results)
    {
        $this->results = $results;

        return $this;
    }

    /**
     * Get course
     *
     * @return int
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * Set course
     *
     * @param integer $course
     *
     * @return Privilege
     */
    public function setCourse($course)
    {
        $this->course = $course;

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
     * Set name
     *
     * @param string $name
     *
     * @return Privilege
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * Set slug
     *
     * @param string $slug
     *
     * @return Privilege
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param string $users
     */
    public function setUsers($users)
    {
        $this->users = $users;
    }

    /**
     * @return int
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param int $site
     */
    public function setSite($site)
    {
        $this->site = $site;
    }



}


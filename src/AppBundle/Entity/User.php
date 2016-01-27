<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @var blob
     *
     * @ORM\Column(name="avatar", type="blob", nullable=true)
     */
    private $avatar;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbPoints", type="integer", nullable=true)
     */
    private $nbPoints;

     /**
     * @ORM\OneToMany(targetEntity="PossessedDevice", mappedBy="user")
     */
     private $possessedDevices;

    /**
     * @ORM\OneToOne(targetEntity="Stats")
     * @ORM\JoinColumn(name="stats_id", referencedColumnName="id")
     */
    private $stats;

    /**
     * @ORM\OneToMany(targetEntity="Challenge", mappedBy="creator")
     */
    private $challengesCreated;

    /**
     * @ORM\ManyToMany(targetEntity="Challenge", inversedBy="challengers")
     * @ORM\JoinTable(name="user_challenge")
     */
    private $challengesAccepted;


    public function __construct() {
        parent::__construct();
        $this->possessedDevices = new ArrayCollection();
        $this->challengesCreated = new ArrayCollection();
        $this->challengesAccepted = new ArrayCollection();
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
     * Set firstname
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }


    /**
     * Set avatar
     *
     * @param string $avatar
     *
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set nbPoints
     *
     * @param integer $nbPoints
     *
     * @return User
     */
    public function setNbPoints($nbPoints)
    {
        $this->nbPoints = $nbPoints;

        return $this;
    }

    /**
     * Get nbPoints
     *
     * @return integer
     */
    public function getNbPoints()
    {
        return $this->nbPoints;
    }

    /**
     * Add possessedDevice
     *
     * @param \AppBundle\Entity\PossessedDevice $possessedDevice
     *
     * @return User
     */
    public function addPossessedDevice(\AppBundle\Entity\PossessedDevice $possessedDevice)
    {
        $this->possessedDevices[] = $possessedDevice;

        return $this;
    }

    /**
     * Remove possessedDevice
     *
     * @param \AppBundle\Entity\PossessedDevice $possessedDevice
     */
    public function removePossessedDevice(\AppBundle\Entity\PossessedDevice $possessedDevice)
    {
        $this->possessedDevices->removeElement($possessedDevice);
    }

    /**
     * Get possessedDevices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPossessedDevices()
    {
        return $this->possessedDevices;
    }

    /**
     * Set stats
     *
     * @param \AppBundle\Entity\Stats $stats
     *
     * @return User
     */
    public function setStats(\AppBundle\Entity\Stats $stats = null)
    {
        $this->stats = $stats;

        return $this;
    }

    /**
     * Get stats
     *
     * @return \AppBundle\Entity\Stats
     */
    public function getStats()
    {
        return $this->stats;
    }

    /**
     * Add challengesCreated
     *
     * @param \AppBundle\Entity\Challenge $challengesCreated
     *
     * @return User
     */
    public function addChallengesCreated(\AppBundle\Entity\Challenge $challengesCreated)
    {
        $this->challengesCreated[] = $challengesCreated;

        return $this;
    }

    /**
     * Remove challengesCreated
     *
     * @param \AppBundle\Entity\Challenge $challengesCreated
     */
    public function removeChallengesCreated(\AppBundle\Entity\Challenge $challengesCreated)
    {
        $this->challengesCreated->removeElement($challengesCreated);
    }

    /**
     * Get challengesCreated
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChallengesCreated()
    {
        return $this->challengesCreated;
    }

    /**
     * Add challengesAccepted
     *
     * @param \AppBundle\Entity\Challenge $challengesAccepted
     *
     * @return User
     */
    public function addChallengesAccepted(\AppBundle\Entity\Challenge $challengesAccepted)
    {
        $this->challengesAccepted[] = $challengesAccepted;

        return $this;
    }

    /**
     * Remove challengesAccepted
     *
     * @param \AppBundle\Entity\Challenge $challengesAccepted
     */
    public function removeChallengesAccepted(\AppBundle\Entity\Challenge $challengesAccepted)
    {
        $this->challengesAccepted->removeElement($challengesAccepted);
    }

    /**
     * Get challengesAccepted
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChallengesAccepted()
    {
        return $this->challengesAccepted;
    }
}

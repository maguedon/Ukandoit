<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User_Challenge
 *
 * @ORM\Table(name="user_challenge")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\User_ChallengeRepository")
 */
class User_Challenge
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="challengesAccepted")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $challenger;

    /**
     * @ORM\ManyToOne(targetEntity="Challenge", inversedBy="userChallenges")
     * @ORM\JoinColumn(name="challenge_id", referencedColumnName="id")
     */
    private $challenge;

    /**
     * @ORM\ManyToOne(targetEntity="PossessedDevice")
     * @ORM\JoinColumn(name="possessedDevice_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $deviceUsed;

    /**
     * @var boolean
     *
     * @ORM\Column(name="disqualified", type="boolean")
     */
    private $disqualified;

    /**
     * @var int
     *
     * @ORM\Column(name="performance", type="integer")
     */
    private $performance;


    public function __construct() {
        $this->disqualified = false;
        $this->performance = 0;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set challenger
     *
     * @param \AppBundle\Entity\User $challenger
     * @return User_Challenge
     */
    public function setChallenger(\AppBundle\Entity\User $challenger = null)
    {
        $this->challenger = $challenger;

        return $this;
    }

    /**
     * Get challenger
     *
     * @return \AppBundle\Entity\User 
     */
    public function getChallenger()
    {
        return $this->challenger;
    }

    /**
     * Set challenge
     *
     * @param \AppBundle\Entity\Challenge $challenge
     * @return User_Challenge
     */
    public function setChallenge(\AppBundle\Entity\Challenge $challenge = null)
    {
        $this->challenge = $challenge;

        return $this;
    }

    /**
     * Get challenge
     *
     * @return \AppBundle\Entity\Challenge 
     */
    public function getChallenge()
    {
        return $this->challenge;
    }

    /**
     * Set deviceUsed
     *
     * @param \AppBundle\Entity\PossessedDevice $deviceUsed
     * @return User_Challenge
     */
    public function setDeviceUsed(\AppBundle\Entity\PossessedDevice $deviceUsed = null)
    {
        $this->deviceUsed = $deviceUsed;

        return $this;
    }

    /**
     * Get deviceUsed
     *
     * @return \AppBundle\Entity\PossessedDevice 
     */
    public function getDeviceUsed()
    {
        return $this->deviceUsed;
    }


    /**
     * Set disqualified
     *
     * @param boolean $disqualified
     *
     * @return User_Challenge
     */
    public function setDisqualified($disqualified)
    {
        $this->disqualified = $disqualified;

        return $this;
    }

    /**
     * Get disqualified
     *
     * @return boolean
     */
    public function getDisqualified()
    {
        return $this->disqualified;
    }

    /**
     * Set performance
     *
     * @param integer $performance
     *
     * @return User_Challenge
     */
    public function setPerformance($performance)
    {
        $this->performance = $performance;

        return $this;
    }

    /**
     * Get performance
     *
     * @return integer
     */
    public function getPerformance()
    {
        return $this->performance;
    }
}

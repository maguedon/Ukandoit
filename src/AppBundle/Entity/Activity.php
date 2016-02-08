<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Activity
 *
 * @ORM\Table(name="activity")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ActivityRepository")
 */
class Activity
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Challenge", mappedBy="activity")
     */
    private $challenges;


    public function __construct() {
        $this->challenges = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Activity
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
     * Add challenge
     *
     * @param \AppBundle\Entity\Challenge $challenge
     *
     * @return Activity
     */
    public function addChallenge(\AppBundle\Entity\Challenge $challenge)
    {
        $this->challenges[] = $challenge;

        return $this;
    }

    /**
     * Remove challenge
     *
     * @param \AppBundle\Entity\Challenge $challenge
     */
    public function removeChallenge(\AppBundle\Entity\Challenge $challenge)
    {
        $this->challenges->removeElement($challenge);
    }

    /**
     * Get challenges
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChallenges()
    {
        return $this->challenges;
    }

    public function __toString(){
        return $this->getName();
    }
}

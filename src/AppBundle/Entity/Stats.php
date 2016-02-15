<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stats
 *
 * @ORM\Table(name="stats")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StatsRepository")
 */
class Stats
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
     * @ORM\Column(name="nbWin", type="integer")
     */
    private $nbWin;

    /**
     * @var int
     *
     * @ORM\Column(name="nbPlayed", type="integer")
     */
    private $nbPlayed;

    /**
     * @var int
     *
     * @ORM\Column(name="nbKmWalked", type="integer")
     */
    private $nbKmWalked;

    public function __construct(){
        $this->nbWin = 0;
        $this->nbPlayed = 0;
        $this->nbKmWalked = 0;
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
     * Set nbWin
     *
     * @param integer $nbWin
     *
     * @return Stats
     */
    public function setNbWin($nbWin)
    {
        $this->nbWin = $nbWin;

        return $this;
    }

    /**
     * Get nbWin
     *
     * @return int
     */
    public function getNbWin()
    {
        return $this->nbWin;
    }

    /**
     * Add one Win
     */
    public function addWin(){
        $this->nbWin += 1;
    }

    /**
     * Set nbPlayed
     *
     * @param integer $nbPlayed
     *
     * @return Stats
     */
    public function setNbPlayed($nbPlayed)
    {
        $this->nbPlayed = $nbPlayed;

        return $this;
    }

    /**
     * Get nbPlayed
     *
     * @return int
     */
    public function getNbPlayed()
    {
        return $this->nbPlayed;
    }

    /**
     * Add one Challenge Played
     */
    public function addChallengePlayed(){
        $this->nbPlayed += 1;
    }

    /**
     * Set nbKmWalked
     *
     * @param integer $nbKmWalked
     *
     * @return Stats
     */
    public function setNbKmWalked($nbKmWalked)
    {
        $this->nbKmWalked = $nbKmWalked;

        return $this;
    }

    /**
     * Get nbKmWalked
     *
     * @return int
     */
    public function getNbKmWalked()
    {
        return $this->nbKmWalked;
    }
}

<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Level
 *
 * @ORM\Table(name="level")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LevelRepository")
 */
class Level
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
     * @ORM\Column(name="numLevel", type="integer", unique=true)
     */
    private $numLevel;

    /**
     * @var int
     *
     * @ORM\Column(name="nbPoints", type="integer", unique=true)
     */
    private $nbPoints;


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
     * Set numLevel
     *
     * @param integer $numLevel
     *
     * @return Level
     */
    public function setNumLevel($numLevel)
    {
        $this->numLevel = $numLevel;

        return $this;
    }

    /**
     * Get numLevel
     *
     * @return int
     */
    public function getNumLevel()
    {
        return $this->numLevel;
    }

    /**
     * Set nbPoints
     *
     * @param integer $nbPoints
     *
     * @return Level
     */
    public function setNbPoints($nbPoints)
    {
        $this->nbPoints = $nbPoints;

        return $this;
    }

    /**
     * Get nbPoints
     *
     * @return int
     */
    public function getNbPoints()
    {
        return $this->nbPoints;
    }

    public function __toString(){
        return "level " . $this->numLevel . " : " . $this->nbPoints;
    }
}

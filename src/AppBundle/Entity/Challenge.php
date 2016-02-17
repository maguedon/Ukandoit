<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Challenge
 *
 * @ORM\Table(name="challenge")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ChallengeRepository")
 */
class Challenge
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
     * @var \DateTime
     *
     * @ORM\Column(name="creationDate", type="datetime")
     */
    private $creationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endDate", type="datetime")
     */
    private $endDate;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="challengesCreated")
     * @ORM\JoinColumn(name="creator_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $creator;

    /**
     * Challengers
     *
     * @ORM\OneToMany(targetEntity="User_Challenge", mappedBy="challenge")
     */
    private $userChallenges;

    /**
     * @ORM\ManyToOne(targetEntity="Activity", inversedBy="challenges")
     * @ORM\JoinColumn(name="activity_id", referencedColumnName="id")
     */
    private $activity;

    /**
    * @var int
    *
    * @ORM\Column(name="kilometres", type="integer", nullable=true)
    */
    private $kilometres;

   /**
    * @var int
    *
    * @ORM\Column(name="nb_steps", type="integer", nullable=true)
    */
   private $nbSteps;

   /**
    * En nombre de jours
    * @var int
    *
    * @ORM\Column(name="time", type="integer")
    */
   private $time;

   /**
    * Nombre de points accordés au gagnant
    * @var int
    *
    * @ORM\Column(name="nb_points", type="integer")
    */
   private $nbPoints;

   /**
    * @var string
    *
    * @ORM\Column(name="difficulty", type="string")
    */
   private $difficulty;


   public function __construct() {
    $this->challengers = new ArrayCollection();
    $this->creationDate = new \DateTime();
    $this->kilometres = null;
    $this->nbSteps = null;
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
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return Challenge
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return Challenge
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set creator
     *
     * @param \AppBundle\Entity\User $creator
     *
     * @return Challenge
     */
    public function setCreator(\AppBundle\Entity\User $creator = null)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator
     *
     * @return \AppBundle\Entity\User
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Set activity
     *
     * @param \AppBundle\Entity\Activity $activity
     *
     * @return Challenge
     */
    public function setActivity(\AppBundle\Entity\Activity $activity = null)
    {
        $this->activity = $activity;

        return $this;
    }

    /**
     * Get activity
     *
     * @return \AppBundle\Entity\Activity
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * Add userChallenges
     *
     * @param \AppBundle\Entity\User_Challenge $userChallenges
     * @return Challenge
     */
    public function addUserChallenge(\AppBundle\Entity\User_Challenge $userChallenges)
    {
        $this->userChallenges[] = $userChallenges;

        return $this;
    }

    /**
     * Remove userChallenges
     *
     * @param \AppBundle\Entity\User_Challenge $userChallenges
     */
    public function removeUserChallenge(\AppBundle\Entity\User_Challenge $userChallenges)
    {
        $this->userChallenges->removeElement($userChallenges);
    }

    /**
     * Get userChallenges
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUserChallenges()
    {
        return $this->userChallenges;
    }

    /**
     * Set kilometres
     *
     * @param integer $kilometres
     * @return Challenge
     */
    public function setKilometres($kilometres)
    {
        $this->kilometres = $kilometres;

        return $this;
    }

    /**
     * Get kilometres
     *
     * @return integer 
     */
    public function getKilometres()
    {
        return $this->kilometres;
    }

    /**
     * Set nbSteps
     *
     * @param integer $nbSteps
     * @return Challenge
     */
    public function setNbSteps($nbSteps)
    {
        $this->nbSteps = $nbSteps;

        return $this;
    }

    /**
     * Get nbSteps
     *
     * @return integer 
     */
    public function getNbSteps()
    {
        return $this->nbSteps;
    }

    /**
     * Set time
     *
     * @param integer $time
     * @return Challenge
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return integer 
     */
    public function getTime()
    {
        return $this->time;
    }


    /**
    * Phrase d'accroche des cards de défis
    */
    public function getTeaser(){
        //Je vous défie de courir au moins 30km cette semaine !
        $teaser = "Je vous défie de ";

        if($this->activity == "Course"){
            $teaser .= "courir au moins " . $this->kilometres . "km";
        }
        // Marche
        else{
            if ($this->kilometres != null){
                $teaser .= "marcher au moins " . $this->kilometres . "km";
            }
            // NbPas != null
            else{
                $teaser .= "faire au moins " . $this->nbSteps . " pas";
            }
        }

        $teaser .= " sur " . $this->time;

        if($this->time == 1)
            $teaser .= " journée !";
        else
            $teaser .= " jours !";

        return $teaser;
    }

    /**
     * Titre des cards de défis
     *
     * @return string
     */
    public function getTitle()
    {
        return "Objectif " . (($this->nbSteps != null) ? ($this->nbSteps . " pas") : ($this->kilometres . "km")) . " ! " ;
    }


    /**
     * Set nbPoints
     *
     * @param integer $nbPoints
     *
     * @return Challenge
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
     * Set difficulty
     *
     *
     * @return Challenge
     */
    public function setDifficulty()
    {
        // < 8 000 pas -> facile // 6km
        // entre 8 000 pas et 12 000 pas -> moyen  // 6km à 10km
        // > 12 000 pas -> difficile // > 10km

        if($this->getTime() == 1){
            if($this->getKilometres() != null && $this->getKilometres() != 0)
                $this->setDifficultyByKilometers($this->kilometres);
            // En nombre de pas
            else
                $this->setDifficultyBySteps($this->steps);
        }
        // Si le challenge est sur plusieurs jours
        else{
            if($this->getKilometres() != null && $this->getKilometres() != 0){
                $km_one_day = $this->kilometres / $this->time;
                $this->setDifficultyByKilometers($km_one_day); 
            }
            // En nombre de pas
            else{
                $steps_one_day = $this->steps / $this->time;
                $this->setDifficultyBySteps($steps_one_day);  
            }
        }

        return $this;
    }

    public function setDifficultyByKilometers($km){
        if ($km <= 6) 
            $this->difficulty = "easy";
        else if(6 < $km && $km <= 10)
            $this->difficulty = "medium";
        else
            $this->difficulty = "hard";
    }

    public function setDifficultyBySteps($steps){
        if ($steps <= 8000) 
            $this->difficulty = "easy";
        else if(8000 < $steps && $steps <= 12000)
            $this->difficulty = "medium";
        else
            $this->difficulty = "hard";
    }

    /**
     * Get difficulty
     *
     * @return string
     */
    public function getDifficulty()
    {
        return $this->difficulty;
    }
}

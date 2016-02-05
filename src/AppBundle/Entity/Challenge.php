<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

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
     * @ORM\Column(name="nb_points_first", type="integer")
     */
    private $nbPointsFirst;

    /**
     * Nombre de points accordés au deuxième
     * @var int
     *
     * @ORM\Column(name="nb_points_second", type="integer")
     */
    private $nbPointsSecond;

    /**
     * Nombre de points accordés au troisième
     * @var int
     *
     * @ORM\Column(name="nb_points_third", type="integer")
     */
    private $nbPointsThird;


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
     * Set title
     *
     * @param string $title
     *
     * @return Challenge
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
     * Add challenger
     *
     * @param \AppBundle\Entity\User $challenger
     *
     * @return Challenge
     */
    public function addChallenger(\AppBundle\Entity\User $challenger)
    {
        $this->challengers[] = $challenger;

        return $this;
    }

    /**
     * Remove challenger
     *
     * @param \AppBundle\Entity\User $challenger
     */
    public function removeChallenger(\AppBundle\Entity\User $challenger)
    {
        $this->challengers->removeElement($challenger);
    }

    /**
     * Get challengers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChallengers()
    {
        return $this->challengers;
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
     * @param \DateTime $time
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
     * @return \DateTime 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set nbPointsFirst
     *
     * @param integer $nbPointsFirst
     * @return Challenge
     */
    public function setNbPointsFirst($nbPointsFirst)
    {
        $this->nbPointsFirst = $nbPointsFirst;

        return $this;
    }

    /**
     * Get nbPointsFirst
     *
     * @return integer 
     */
    public function getNbPointsFirst()
    {
        return $this->nbPointsFirst;
    }

    /**
     * Set nbPointsSecond
     *
     * @param integer $nbPointsSecond
     * @return Challenge
     */
    public function setNbPointsSecond($nbPointsSecond)
    {
        $this->nbPointsSecond = $nbPointsSecond;

        return $this;
    }

    /**
     * Get nbPointsSecond
     *
     * @return integer 
     */
    public function getNbPointsSecond()
    {
        return $this->nbPointsSecond;
    }

    /**
     * Set nbPointsThird
     *
     * @param integer $nbPointsThird
     * @return Challenge
     */
    public function setNbPointsThird($nbPointsThird)
    {
        $this->nbPointsThird = $nbPointsThird;

        return $this;
    }

    /**
     * Get nbPointsThird
     *
     * @return integer 
     */
    public function getNbPointsThird()
    {
        return $this->nbPointsThird;
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
}

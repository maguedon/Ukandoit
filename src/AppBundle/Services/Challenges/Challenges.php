<?php

namespace AppBundle\Services\Challenges;

use Doctrine\ORM\EntityManager;

class Challenges
{
	protected $em;

	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}

	//Récupération des 9 défis les plus populaires
	public function getBestChallenges(){
		//Version résultat SQL
		$bestsChallengesSQL = $this->em->getRepository('AppBundle:Challenge')
		->findByBests();

		//Transformation en tableau d'objets doctrine
		$finalBestsChallenges = array();
		foreach($bestsChallengesSQL as $challenge){
			$current_challenge = $this->em->getRepository('AppBundle:Challenge')->find($challenge['id']);
			array_push($finalBestsChallenges, $current_challenge);
		}

		return $finalBestsChallenges;
	}

	//Récupération des défis terminés d'un utilisateur
	public function getFinishedChallenges($user){
		$challenges = $user->getChallengesAccepted();
		$finishedChallenges = array();

		foreach($challenges as $challenge){
			if($challenge->getChallenge()->getEndDate() < new \DateTime())
				array_push($finishedChallenges, $challenge->getChallenge());
		}
		return $finishedChallenges;
	}

	//Récupération des défis non terminés d'un utilisateur
	public function getNotFinishedChallenges($user){
		$challenges = $user->getChallengesAccepted();
		$notFinishedChallenges = array();

		foreach($challenges as $challenge){
			if($challenge->getChallenge()->getEndDate() >= new \DateTime())
				array_push($notFinishedChallenges, $challenge->getChallenge());
		}
		return $notFinishedChallenges;
	}
}
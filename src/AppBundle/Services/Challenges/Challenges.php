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

	//Récupération des 9 derniers défis
	public function getLastChallenges(){
		return $this->em->getRepository('AppBundle:Challenge')
		->findBy(
                   array(),								// $where
                   array('creationDate' => 'DESC'),		// $orderBy
                   9,									// $limit
                   0									// $offset
                   );
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
}
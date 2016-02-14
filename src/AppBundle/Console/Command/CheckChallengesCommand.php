<?php

namespace AppBundle\Console\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CheckChallengesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
        ->setName('challenges:check')
        ->setDescription('Check the challenges')
        ->addArgument(
            'name',
            InputArgument::OPTIONAL,
            'Who do you want to greet?'
            )
        ->addOption(
         'yell',
         null,
         InputOption::VALUE_NONE,
         'If set, the task will yell in uppercase letters'
         )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $em = $container->get('doctrine')->getManager();

        $challenges = $em->getRepository('AppBundle:Challenge')->findAll();

        //On regarde tous les challenges
        foreach($challenges as $challenge){

            // --------------- Vérification des challenges juste finis -----------------//

            // Si le challenge vient de se terminer on envoie un mail aux participants
            if($challenge->getEndDate()->format('d-m-Y') == (new \DateTime())->modify("-1 day")->format('d-m-Y')){
                foreach($challenge->getUserChallenges() as $user_challenge){
                    $recipient = $user_challenge->getChallenger()->getEmail();
                    $username = $user_challenge->getChallenger()->getUsername();
                    $this->sendEmail($recipient, $username, $challenge);
                }
            }

            // --------------- Vérification des utilisateurs qui ont synchronisé leurs données --------------//

            if($challenge->getEndDate()->modify("+2 day")->format('d-m-Y') == (new \DateTime())->modify("-1 day")->format('d-m-Y')){

                $objective = null;
                $ukandoit = $container->get("app.ukandoit");

                $classement = array();

                foreach($challenge->getUserChallenges() as $user_challenge){

                    $deviceUsed = $user_challenge->getDeviceUsed();

                    switch($deviceUsed->getDeviceType()){

                        case "Withings Activité Pop":
                            $output->writeln("withings");
                            $withings = $container->get('app.withings');
                            $withings->authenticate($deviceUsed);

                            $activities = $withings->getActivities($deviceUsed->getUserIdWithings(), $challenge->getCreationDate()->format('Y-m-d'), $challenge->getEndDate()->format('Y-m-d'));
                            $performance = $ukandoit->getDataFromAPI($challenge, $activities);
                            if ($challenge->getKilometres() == null || $challenge->getKilometres() == 0) {
                                $objective = $challenge->getKilometres() * 1000;
                                if ($performance >= ($challenge->getKilometres() * 1000))
                                    $success = true;
                                else
                                    $success = false;
                            }
                            else{
                                $objective = $challenge->getNbSteps();
                                if ($performance >= $challenge->getNbSteps())
                                    $success = true;
                                else
                                    $success = false;
                            }

                            break;

                        case "Jawbone UP 24":
                            $output->writeln("jawbone");
                            $jawbone = $container->get('app.jawbone');

                            $activities = $jawbone->getMoves($deviceUsed->getAccessTokenJawbone(), $challenge->getCreationDate()->format('Y-m-d'), $challenge->getEndDate()->format('Y-m-d'));
                            $performance = $ukandoit->getDataFromAPI($challenge, $activities);

                            if ($challenge->getKilometres() == null || $challenge->getKilometres() == 0) {
                                if ($performance >= ($challenge->getKilometres() * 1000))
                                    $success = true;
                                else
                                    $success = false;
                            }
                            else{
                                if ($performance >= $challenge->getNbSteps())
                                    $success = true;
                                else
                                    $success = false;
                            }
                            break;

                        case "Google Fitness":
                            $output->writeln("google");
                            $activities = array();
                                //$performance = $ukandoit->getDataFromAPI($challenge, $activities);
    /*                            if ($challenge->getKilometres() == null || $challenge->getKilometres() == 0) {
                                    if ($performance >= ($challenge->getKilometres() * 1000))
                                        $success = true;
                                    else
                                        $success = false;
                                }
                                else{
                                    if ($performance >= $challenge->getNbSteps())
                                        $success = true;
                                    else
                                        $success = false;
                                    }*/
                                    break;

                        default:
                            $output->writeln("default"); //echo dans la console (printf)
                            $activities = array();
                            break;
                    }

                // S'il n'y a pas de données pour la période demandée, l'utilisateur est disqualifié
                    if(count($activities) == 0){
                        $user_challenge->setDisqualified(true);
                        $em->flush();
                    }
                    else{
                        $classement[$performance] = array(
                            "userid" => $user_challenge->getId(),
                            "performance" => $performance,
                            "successful" => $success
                            );
                    }


                }
                krsort($classement);
                $this->getChallengePoints($classement, $objective, $ukandoit); //attribution des points !!
            }

        }
        return 1;
    }

    protected function sendEmail($recipient, $username, $challenge){
        $message = \Swift_Message::newInstance()
        ->setSubject('Défi terminé')
        ->setFrom('contact.ukandoit@gmail.com')
        ->setTo($recipient)
        ->setBody($this->getContainer()->get('templating')->render('email/end_challenge.html.twig', array(
            'username' => $username,
            'challenge' => $challenge
            )), 'text/html')
        ;

        $this->getContainer()->get('mailer')->send($message);
    }

    protected function getChallengePoints($ranking, $goalPoints, $ukandoit){
        $em = $this->getContainer()->get('doctrine')->getManager();
        $gagnants = array();
        $perdants = array();
        foreach($ranking as $user){
            if ($user["successful"])
                array_push($gagnants, $user);
            else
                array_push($perdants, $user);
        }


        for($i = 0; $i < count($gagnants); $i++){
            $nbGagnants = count($gagnants);
            $winner_user = $this->getDoctrine()->getRepository('AppBundle:User')->find($gagnants[$i]["userid"]);
            $pointsWon = $ukandoit->getPointsFromRanking($i+1, $nbGagnants, $goalPoints, true);

            $winner_user->addPoints($pointsWon);

            $winner_stats = $winner_user->getStats();
            $winner_stats->addWin();
            $winner_stats->addChallengePlayed();

            $em->flush();
        }

        for($i = 0; $i < count($perdants); $i++){
            $nbPerdants = count($perdants);
            $loser_user = $this->getDoctrine()->getRepository('AppBundle:User')->find($perdants[$i]["userid"]);
            $pointsWon = $ukandoit->getPointsFromRanking($i+1, $nbPerdants, $goalPoints, false);

            $loser_user->addPoints($pointsWon);

            $loser_stats = $loser_user->getStats();
            $loser_stats->addChallengePlayed();

            $em->flush();
        }

    }

}
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
                foreach($challenge->getUserChallenges() as $user_challenge){
                    $deviceUsed = $user_challenge->getDeviceUsed();

                    switch($deviceUsed->getDeviceType()){

                        case "Withings Activité Pop":
                            $withings = $container->get('app.withings');
                            $withings->authenticate($deviceUsed);

                            $activities = $withings->getActivities($deviceUsed->getUserIdWithings(), $challenge->getCreationDate()->format('Y-m-d'), $challenge->getEndDate()->format('Y-m-d'));
                            break;

                        case "Jawbone UP 24":
                            $jawbone = $container->get('app.jawbone');

                            $activities = $jawbone->getMoves($deviceUsed->getAccessTokenJawbone(), $challenge->getCreationDate()->format('Y-m-d'), $challenge->getEndDate()->format('Y-m-d'));
                            break;

                        case "Googlefit":
                            $activities = array();
                            break;
                            
                        default:
                            $output->writeln("default");
                            $activities = array();
                            break;
                    }

                    // S'il n'y a pas de données pour la période demandée, l'utilisateur est disqualifié
                    if(count($activities) == 0){
                        $user_challenge->setDisqualified(true);
                        $em->flush();
                    }
                }
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
}
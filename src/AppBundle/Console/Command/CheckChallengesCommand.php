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

        // --------------- Vérification des challenges finis -----------------//
        $em = $container->get('doctrine')->getManager();
        $challenges = $em->getRepository('AppBundle:Challenge')->findAll();

        //On regarde tous les challenges
        foreach($challenges as $challenge){
            // Si le challenge vient de se terminer on envoie un mail
            if($challenge->getEndDate()->format('d-m-Y') == (new \DateTime())->modify("-1 day")->format('d-m-Y')){
                foreach($challenge->getUserChallenges() as $user_challenge){
                    $recipient = $user_challenge->getChallenger()->getEmail();
                    $username = $user_challenge->getChallenger()->getUsername();
                    $this->sendEmail($recipient, $username, $challenge);
                }
            }
        }


        $output->writeln("Check ok");
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
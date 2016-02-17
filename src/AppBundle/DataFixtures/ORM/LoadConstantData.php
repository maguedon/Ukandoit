<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\DeviceType;
use AppBundle\Entity\User;
use AppBundle\Entity\Level;
use AppBundle\Entity\Activity;
use AppBundle\Entity\Challenge;
use AppBundle\Entity\Image;
use AppBundle\Entity\Stats;
use Symfony\Component\HttpFoundation\File\File;

class LoadConstantData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    public function load(ObjectManager $manager)
    {

        // -------------- DeviceTypes -------------- //

        $withings_activite_pop = new DeviceType();
        $withings_activite_pop->setName("Withings Activité Pop");
        $manager->persist($withings_activite_pop);

        $jawbone_up_24 = new DeviceType();
        $jawbone_up_24->setName("Jawbone UP 24");
        $manager->persist($jawbone_up_24);

        // -------------- Levels -------------- //

        $level0 = new Level();
        $level0->setNumLevel(0);
        $level0->setNbPoints(0);

        $manager->persist($level0);

        $level1 = new Level();
        $level1->setNumLevel(1);
        $level1->setNbPoints(10);

        $manager->persist($level1);

        $prevPoints = $level1->getNbPoints();
        $prevLevel = $level1;

        for($i=2; $i<=50; $i++){
            $gain = ($prevPoints * 1.2);

            $level = new Level();
            $level->setNumLevel($i);
            $level->setNbPoints(intval($prevLevel->getNbPoints() + $gain));
<<<<<<< HEAD

            $manager->persist($level);

            $prevPoints = $gain;
            $prevLevel = $level;
        }
=======
>>>>>>> master

            $manager->persist($level);

            $prevPoints = $gain;
            $prevLevel = $level;
        }

        // -------------- Activities -------------- //

        $activity = new Activity();
        $activity->setName("marche");
        $manager->persist($activity);

        // -------------- Users -------------- //

        // -------------- Activities -------------- //

        $activity = new Activity();
        $activity->setName("marche");
        $manager->persist($activity);

        // -------------- Users -------------- //

        $levels = $manager->getRepository('AppBundle:Level')->findAll();
        $admin = new User();
        $admin->setUsername("admin");
        $admin->setNbPoints(234, $levels);
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($admin);
        $admin->setPassword($encoder->encodePassword('admin', $admin->getSalt()));
        $admin->setEmail("contact.ukandoit@gmail.com");
        $admin->setEnabled(true);
        $admin->setSuperAdmin(true);

        $statsAdmin = new Stats();
        $admin->setStats($statsAdmin);

        $manager->persist($admin);

        $jeremy = new User();
        $jeremy->setUsername("Jérémy");
        $jeremy->setFirstname("Jérémy");
        $jeremy->setLastname("Vincent");
        $jeremy->setNbPoints(134, $levels);
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($jeremy);
        $jeremy->setPassword($encoder->encodePassword('Jérémy', $jeremy->getSalt()));
        $jeremy->setEmail("jeremy.vnt@gmail.com");
        $jeremy->setEnabled(true);
        $jeremy->setSuperAdmin(true);
        $image = new Image();
        $image->setImageFile('Jeremy_Vincent.jpg');
        $image->setImageName('Jeremy_Vincent.jpg');
        $jeremy->setAvatar($image);

        $statsJeremy = new Stats();
        $jeremy->setStats($statsJeremy);

        $manager->persist($jeremy);

        $juliette = new User();
        $juliette->setUsername("Juliette");
        $juliette->setFirstname("Juliette");
        $juliette->setLastname("Rivière");
        $juliette->setNbPoints(234, $levels);
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($juliette);
        $juliette->setPassword($encoder->encodePassword('Juliette', $juliette->getSalt()));
        $juliette->setEmail("juliette@riviere.im");
        $juliette->setEnabled(true);
        $juliette->setSuperAdmin(true);
        $image = new Image();
        $image->setImageFile('Juliette_Riviere.jpg');
        $image->setImageName('Juliette_Riviere.jpg');
        $juliette->setAvatar($image);

        $statsJuliette = new Stats();
        $juliette->setStats($statsJuliette);

        $manager->persist($juliette);


        $mathilde = new User();
        $mathilde->setUsername("Mathilde");
        $mathilde->setFirstname("Mathilde");
        $mathilde->setLastname("Guédon");
        $mathilde->setNbPoints(334, $levels);
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($mathilde);
        $mathilde->setPassword($encoder->encodePassword('Mathilde', $mathilde->getSalt()));
        $mathilde->setEmail("maguedon@laposte.net");
        $mathilde->setEnabled(true);
        $mathilde->setSuperAdmin(true);
        $image = new Image();
        $image->setImageFile('Mathilde_Guedon.jpg');
        $image->setImageName('Mathilde_Guedon.jpg');
        $mathilde->setAvatar($image);

        $statsMathilde = new Stats();
        $mathilde->setStats($statsMathilde);

        $manager->persist($mathilde);


        $stephane = new User();
        $stephane->setUsername("Stéphane");
        $stephane->setFirstname("Stéphane");
        $stephane->setLastname("Bourdier");
        $stephane->setNbPoints(400, $levels);
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($stephane);
        $stephane->setPassword($encoder->encodePassword('Stéphane', $stephane->getSalt()));
        $stephane->setEmail("sbourdier.sio@gmail.com");
        $stephane->setEnabled(true);
        $stephane->setSuperAdmin(true);
        $image = new Image();
        $image->setImageFile('Stephane_Bourdier.jpg');
        $image->setImageName('Stephane_Bourdier.jpg');
        $stephane->setAvatar($image);
<<<<<<< HEAD

        $statsStephane = new Stats();
        $stephane->setStats($statsStephane);
=======

        $statsStephane = new Stats();
        $stephane->setStats($statsStephane);

        $manager->persist($stephane);

        // -------------- Challenges -------------- //

        $defi1 = new Challenge();
        $defi1->setEndDate(new \DateTime("2016-02-15"));
        $defi1->setCreator($mathilde);
        $defi1->setActivity($activity);
        $defi1->setTime(1);
        $defi1->setKilometres(5);
        $defi1->setNbPoints(20);

        $defi2 = new Challenge();
        $defi2->setEndDate(new \DateTime("2016-02-19"));
        $defi2->setCreator($juliette);
        $defi2->setActivity($activity);
        $defi2->setTime(1);
        $defi2->setNbSteps(15000);
        $defi2->setNbPoints(30);

        $defi3 = new Challenge();
        $defi3->setEndDate(new \DateTime("2016-02-18"));
        $defi3->setCreator($jeremy);
        $defi3->setActivity($activity);
        $defi3->setTime(5);
        $defi3->setNbSteps(40000);
        $defi3->setNbPoints(20);

        $defi4 = new Challenge();
        $defi4->setEndDate(new \DateTime("2016-02-26"));
        $defi4->setCreator($admin);
        $defi4->setActivity($activity);
        $defi4->setTime(7);
        $defi4->setKilometres(100);
        $defi4->setNbPoints(60);

        $defi5 = new Challenge();
        $defi5->setEndDate(new \DateTime("2016-02-23"));
        $defi5->setCreator($stephane);
        $defi5->setActivity($activity);
        $defi5->setTime(4);
        $defi5->setKilometres(20);
        $defi5->setNbPoints(50);

        $defi6 = new Challenge();
        $defi6->setEndDate(new \DateTime("2016-03-01"));
        $defi6->setCreator($jeremy);
        $defi6->setActivity($activity);
        $defi6->setTime(3);
        $defi6->setKilometres(17);
        $defi6->setNbPoints(50);

        $manager->persist($defi1);
        $manager->persist($defi2);
        $manager->persist($defi3);
        $manager->persist($defi4);
        $manager->persist($defi5);
        $manager->persist($defi6);
>>>>>>> master

        $manager->persist($stephane);
        
        $manager->flush();
    }
}
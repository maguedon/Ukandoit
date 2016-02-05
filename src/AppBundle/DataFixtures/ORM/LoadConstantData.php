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
        $withings_activite_pop = new DeviceType();
        $withings_activite_pop->setName("Withings Activité Pop");
        $manager->persist($withings_activite_pop);

        $jawbone_up_24 = new DeviceType();
        $jawbone_up_24->setName("Jawbone UP 24");
        $manager->persist($jawbone_up_24);

        $level0 = new Level();
        $level0->setNumLevel(0);
        $level0->setNbPoints(0);

        $level1 = new Level();
        $level1->setNumLevel(1);
        $level1->setNbPoints(100);

        $level2 = new Level();
        $level2->setNumLevel(2);
        $level2->setNbPoints(200);

        $level3 = new Level();
        $level3->setNumLevel(3);
        $level3->setNbPoints(300);

        $level4 = new Level();
        $level4->setNumLevel(4);
        $level4->setNbPoints(400);

        $manager->persist($level0);
        $manager->persist($level1);
        $manager->persist($level2);
        $manager->persist($level3);
        $manager->persist($level4);

        $manager->flush();

        $levels = $manager->getRepository('AppBundle:Level')->findAll();
        $admin = new User();
        $admin->setUsername("admin");
        $admin->setNbPoints(234, $levels);
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($admin);
        $admin->setPassword($encoder->encodePassword('admin', $admin->getSalt()));
        $admin->setEmail("contact.ukandoit@gmail.com");
        $admin->setEnabled(true);
        $admin->setSuperAdmin(true);
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
        $file = new File('web/images/avatars/Jeremy_Vincent.jpg');
        $image->setImageFile($file);
        $image->setImageName('Jeremy_Vincent.jpg');
        $jeremy->setAvatar($image);
        $manager->persist($jeremy);


        $juliette = new User();
        $juliette->setUsername("Juliette");
        $juliette->setFirstname("Juliette");
        $juliette->setLastname("Rivière");
        $juliette->setNbPoints(234, $levels);
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($juliette);
        $juliette->setPassword($encoder->encodePassword('Juliette', $juliette->getSalt()));
        $juliette->setEmail("Juliette.ukandoit@gmail.com");
        $juliette->setEnabled(true);
        $juliette->setSuperAdmin(true);
        $image = new Image();
        $file = new File('web/images/avatars/Juliette_Riviere.jpg');
        $image->setImageFile($file);
        $image->setImageName('Juliette_Riviere.jpg');
        $juliette->setAvatar($image);
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
        $file = new File('web/images/avatars/Mathilde_Guedon.jpg');
        $image->setImageFile($file);
        $image->setImageName('Mathilde_Guedon.jpg');
        $mathilde->setAvatar($image);
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
        $file = new File('web/images/avatars/Stephane_Bourdier.jpg');
        $image->setImageFile($file);
        $image->setImageName('Stephane_Bourdier.jpg');
        $stephane->setAvatar($image);
        $manager->persist($stephane);

        $activity = new Activity();
        $activity->setName("course");
        $manager->persist($activity);

        $defis1 = new Challenge();
        $defis1->setEndDate(new \DateTime("2016-03-04"));
        $defis1->setCreator($mathilde);
        $defis1->setTitle("Objectif 10 kilomètres !");
        $defis1->setActivity($activity);
        $defis1->setTime(1);
        $defis1->setKilometres(10);
        $defis1->setNbPointsFirst(50);
        $defis1->setNbPointsSecond(40);
        $defis1->setNbPointsThird(30);

        $defis2 = new Challenge();
        $defis2->setEndDate(new \DateTime("2016-04-05"));
        $defis2->setCreator($juliette);
        $defis2->setTitle("Objectif 1500 pas !");
        $defis2->setActivity($activity);
        $defis2->setTime(1);
        $defis2->setNbSteps(15000);
        $defis2->setNbPointsFirst(50);
        $defis2->setNbPointsSecond(40);
        $defis2->setNbPointsThird(30);

        $defis3 = new Challenge();
        $defis3->setEndDate(new \DateTime("2016-03-22"));
        $defis3->setCreator($jeremy);
        $defis3->setTitle("Objectif 40000 pas !");
        $defis3->setActivity($activity);
        $defis3->setTime(5);
        $defis3->setNbSteps(40000);
        $defis3->setNbPointsFirst(50);
        $defis3->setNbPointsSecond(40);
        $defis3->setNbPointsThird(30);

        $defis4 = new Challenge();
        $defis4->setEndDate(new \DateTime("2016-05-19"));
        $defis4->setCreator($admin);
        $defis4->setTitle("Objectif 70 kilomètres !");
        $defis4->setActivity($activity);
        $defis4->setTime(7);
        $defis4->setKilometres(100);
        $defis4->setNbPointsFirst(50);
        $defis4->setNbPointsSecond(40);
        $defis4->setNbPointsThird(30);

        $defis5 = new Challenge();
        $defis5->setEndDate(new \DateTime("2016-02-23"));
        $defis5->setCreator($stephane);
        $defis5->setTitle("Objectif 50 kilomètres !");
        $defis5->setActivity($activity);
        $defis5->setTime(4);
        $defis5->setKilometres(60000);
        $defis5->setNbPointsFirst(50);
        $defis5->setNbPointsSecond(40);
        $defis5->setNbPointsThird(30);

        $manager->persist($defis1);
        $manager->persist($defis2);
        $manager->persist($defis3);
        $manager->persist($defis4);
        $manager->persist($defis5);


        $manager->flush();
    }
}
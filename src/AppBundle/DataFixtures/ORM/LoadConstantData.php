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
        $image->setImageFile('Jeremy_Vincent.jpg');
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
        $juliette->setEmail("juliette@riviere.im");
        $juliette->setEnabled(true);
        $juliette->setSuperAdmin(true);
        $image = new Image();
        $image->setImageFile('Juliette_Riviere.jpg');
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
        $image->setImageFile('Mathilde_Guedon.jpg');
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
        $image->setImageFile('Stephane_Bourdier.jpg');
        $image->setImageName('Stephane_Bourdier.jpg');
        $stephane->setAvatar($image);
        $manager->persist($stephane);

        $activity = new Activity();
        $activity->setName("marche");
        $manager->persist($activity);

        $defi1 = new Challenge();
        $defi1->setEndDate(new \DateTime("2016-03-04"));
        $defi1->setCreator($mathilde);
        $defi1->setTitle("1 jour = 10 km");
        $defi1->setActivity($activity);
        $defi1->setTime(1);
        $defi1->setKilometres(10);
        $defi1->setNbPointsFirst(50);
        $defi1->setNbPointsSecond(40);
        $defi1->setNbPointsThird(30);

        $defi2 = new Challenge();
        $defi2->setEndDate(new \DateTime("2016-04-05"));
        $defi2->setCreator($juliette);
        $defi2->setTitle("15 000 pas en un jour !");
        $defi2->setActivity($activity);
        $defi2->setTime(1);
        $defi2->setNbSteps(15000);
        $defi2->setNbPointsFirst(55);
        $defi2->setNbPointsSecond(45);
        $defi2->setNbPointsThird(35);

        $defi3 = new Challenge();
        $defi3->setEndDate(new \DateTime("2016-03-22"));
        $defi3->setCreator($jeremy);
        $defi3->setTitle("Objectif 40 000 pas sur 5 jours !");
        $defi3->setActivity($activity);
        $defi3->setTime(5);
        $defi3->setNbSteps(40000);
        $defi3->setNbPointsFirst(80);
        $defi3->setNbPointsSecond(40);
        $defi3->setNbPointsThird(20);

        $defi4 = new Challenge();
        $defi4->setEndDate(new \DateTime("2016-05-19"));
        $defi4->setCreator($admin);
        $defi4->setTitle("100 kilomètres dans une semaine !");
        $defi4->setActivity($activity);
        $defi4->setTime(7);
        $defi4->setKilometres(100);
        $defi4->setNbPointsFirst(115);
        $defi4->setNbPointsSecond(85);
        $defi4->setNbPointsThird(55);

        $defi5 = new Challenge();
        $defi5->setEndDate(new \DateTime("2016-02-23"));
        $defi5->setCreator($stephane);
        $defi5->setTitle("Objectif 60 000 pas !");
        $defi5->setActivity($activity);
        $defi5->setTime(4);
        $defi5->setKilometres(60000);
        $defi5->setNbPointsFirst(50);
        $defi5->setNbPointsSecond(40);
        $defi5->setNbPointsThird(30);

        $defi6 = new Challenge();
        $defi6->setCreationDate(new \DateTime("2016-01-31"));
        $defi6->setEndDate(new \DateTime("2016-02-08"));
        $defi6->setCreator($jeremy);
        $defi6->setTitle("Objectif 15 km");
        $defi6->setActivity($activity);
        $defi6->setTime(3);
        $defi6->setKilometres(15000);
        $defi6->setNbPointsFirst(50);
        $defi6->setNbPointsSecond(40);
        $defi6->setNbPointsThird(30);

        $manager->persist($defi1);
        $manager->persist($defi2);
        $manager->persist($defi3);
        $manager->persist($defi4);
        $manager->persist($defi5);
        $manager->persist($defi6);

        $manager->flush();
    }
}
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

        $manager->persist($withings_activite_pop);
        $manager->persist($jawbone_up_24);

        $manager->persist($level0);
        $manager->persist($level1);
        $manager->persist($level2);
        $manager->persist($level3);
        $manager->persist($level4);




        $manager->flush();

        $levels = $manager->getRepository('AppBundle:Level')->findAll();
        $admin = new User();
        $admin->setLevels($levels);
        $admin->setUsername("admin");
        $admin->setNbPoints(234);

        $encoder = $this->container
        ->get('security.encoder_factory')
        ->getEncoder($admin)        ;
        $admin->setPassword($encoder->encodePassword('admin', $admin->getSalt()));

        $admin->setEmail("contact.ukandoit@gmail.com");
        $admin->setEnabled(true);
        $admin->setSuperAdmin(true);
        $manager->persist($admin);

        $jeremy = new User();
        $jeremy->setLevels($levels);
        $jeremy->setUsername("Jérémy");
        $jeremy->setNbPoints(134);

        $encoder = $this->container
        ->get('security.encoder_factory')
        ->getEncoder($jeremy)
        ;
        $jeremy->setPassword($encoder->encodePassword('Jérémy', $jeremy->getSalt()));

        $jeremy->setEmail("Jérémy.ukandoit@gmail.com");
        $jeremy->setEnabled(true);
        $jeremy->setSuperAdmin(true);
        $manager->persist($jeremy);


        $juliette = new User();
        $juliette->setLevels($levels);
        $juliette->setUsername("Juliette");
        $juliette->setNbPoints(234);

        $encoder = $this->container
        ->get('security.encoder_factory')
        ->getEncoder($juliette)
        ;
        $juliette->setPassword($encoder->encodePassword('Juliette', $juliette->getSalt()));

        $juliette->setEmail("Juliette.ukandoit@gmail.com");
        $juliette->setEnabled(true);
        $juliette->setSuperAdmin(true);
        $manager->persist($juliette);


        $mathilde = new User();
        $mathilde->setLevels($levels);
        $mathilde->setUsername("Mathilde");
        $mathilde->setNbPoints(334);


        $encoder = $this->container
        ->get('security.encoder_factory')
        ->getEncoder($mathilde)
        ;
        $mathilde->setPassword($encoder->encodePassword('Mathilde', $mathilde->getSalt()));

        $mathilde->setEmail("Mathilde.ukandoit@gmail.com");
        $mathilde->setEnabled(true);  

        $manager->persist($mathilde);


        $stephane = new User();
        $stephane->setLevels($levels);
        $stephane->setUsername("Stéphane");
        $stephane->setNbPoints(400);

        $encoder = $this->container
        ->get('security.encoder_factory')
        ->getEncoder($stephane)
        ;
        $stephane->setPassword($encoder->encodePassword('Stéphane', $stephane->getSalt()));

        $stephane->setEmail("Stéphane.ukandoit@gmail.com");
        $stephane->setEnabled(true);
        $stephane->setSuperAdmin(true);
        $manager->persist($stephane);



        $activity = new Activity();
        $activity->setName("course");
        $manager->persist($activity);


        $defis1 = new Challenge();
        $defis1->setEndDate(new \DateTime(2016-03-04));
        $defis1->setCreator($mathilde);
        $defis1->setTitle("Objectif 10 kilomètres !");
        $defis1->setActivity($activity);

        $defis2 = new Challenge();
        $defis2->setEndDate(new \DateTime(2016-04-05));
        $defis2->setCreator($juliette);
        $defis2->setTitle("Objectif 20 kilomètres !");
        $defis2->setActivity($activity);

        $defis3 = new Challenge();
        $defis3->setEndDate(new \DateTime(2016-03-22));
        $defis3->setCreator($jeremy);
        $defis3->setTitle("Objectif 30 kilomètres !");
        $defis3->setActivity($activity);

        $defis4 = new Challenge();
        $defis4->setEndDate(new \DateTime(2016-05-19));
        $defis4->setCreator($admin);
        $defis4->setTitle("Objectif 40 kilomètres !");
        $defis4->setActivity($activity);

        $defis5 = new Challenge();
        $defis5->setEndDate(new \DateTime(2016-02-23));
        $defis5->setCreator($stephane);
        $defis5->setTitle("Objectif 50 kilomètres !");
        $defis5->setActivity($activity);

        $manager->persist($defis1);
        $manager->persist($defis2);
        $manager->persist($defis3);
        $manager->persist($defis4);
        $manager->persist($defis5);


        $manager->flush();
    }
}
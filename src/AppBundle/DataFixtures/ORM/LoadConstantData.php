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


		$coucou = new User();
		$coucou->setUsername("coucou");
		$coucou->setNbPoints(666);


		$encoder = $this->container
			->get('security.encoder_factory')
			->getEncoder($coucou)
			;
		$coucou->setPassword($encoder->encodePassword('coucou', $coucou->getSalt()));
		
		$coucou->setEmail("coucou.ukandoit@gmail.com");
		$coucou->setEnabled(true);	


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
		$manager->persist($admin);	

		$manager->persist($level0);
		$manager->persist($level1);
		$manager->persist($level2);
		$manager->persist($level3);
		$manager->persist($level4);
		$manager->persist($coucou);


		$activity = new Activity();
		$activity->setName("marche");

		$defis1 = new Challenge();
		$defis1->setCreator($coucou);
		$defis1->setTitle("Objectif 10 kilomètres !");
		$defis1->setActivity($activity);

		$defis2 = new Challenge();
		$defis2->setCreator($admin);
		$defis2->setTitle("Objectif 20 kilomètres !");
		$defis2->setActivity($activity);

		$defis3 = new Challenge();
		$defis3->setCreator($coucou);
		$defis3->setTitle("Objectif 30 kilomètres !");
		$defis3->setActivity($activity);

		$defis4 = new Challenge();
		$defis4->setCreator($admin);
		$defis4->setTitle("Objectif 40 kilomètres !");
		$defis4->setActivity($activity);

		$manager->persist($activity);
		$manager->persist($defis1);
		$manager->persist($defis2);
		$manager->persist($defis3);
		$manager->persist($defis4);


		$manager->flush();

		$levels = $manager->getRepository('AppBundle:Level')->findAll();
		$admin = new User($levels);
		$admin->setUsername("admin");
		$admin->setNbPoints(234);

		$encoder = $this->container
			->get('security.encoder_factory')
			->getEncoder($admin)
			;
		$admin->setPassword($encoder->encodePassword('admin', $admin->getSalt()));
		
		$admin->setEmail("contact.ukandoit@gmail.com");
		$admin->setEnabled(true);
		$admin->setSuperAdmin(true);
		$manager->persist($admin);

		$test = new User($levels);
		$test->setUsername("test");
		$test->setNbPoints(234);

		$encoder = $this->container
			->get('security.encoder_factory')
			->getEncoder($test)
			;
		$test->setPassword($encoder->encodePassword('test', $test->getSalt()));
		
		$test->setEmail("test.ukandoit@gmail.com");
		$test->setEnabled(true);
		$test->setSuperAdmin(true);
		$manager->persist($test);

		$activity = new Activity();
		$activity->setName("course");
		$manager->persist($activity);

		$challenge = new Challenge();
		$challenge->setTitle("challenge");
		$challenge->setEndDate(new \DateTime("2016-02-10"));
		$challenge->setCreator($test);
		$challenge->setActivity($activity);
		$manager->persist($challenge);

		$challenge2 = new Challenge();
		$challenge2->setTitle("challenge2");
		$challenge2->setEndDate(new \DateTime("2016-02-15"));
		$challenge2->setCreator($admin);
		$challenge2->setActivity($activity);
		$manager->persist($challenge2);

		$manager->flush();
	}
}
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

		$googlefit = new DeviceType();
		$googlefit->setName("Google Fitness");
		$manager->persist($googlefit);

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
		$admin->setLevels($levels);
		$admin->setUsername("admin");
		$admin->setUsernameCanonical("admin");
		$admin->setNbPoints(234);

		$encoder = $this->container
			->get('security.encoder_factory')
			->getEncoder($admin)
			;
		$admin->setPassword($encoder->encodePassword('admin', $admin->getSalt()));
		
		$admin->setEmail("contact.ukandoit@gmail.com");
		$admin->setEmailCanonical("contact.ukandoit@gmail.com");
		$admin->setEnabled(true);
		$admin->setSuperAdmin(true);
		$manager->persist($admin);

		$test = new User();
		$test->setLevels($levels);
		$test->setUsername("test");
		$test->setUsernameCanonical("test");
		$test->setNbPoints(234);

		$encoder = $this->container
			->get('security.encoder_factory')
			->getEncoder($test)
			;
		$test->setPassword($encoder->encodePassword('test', $test->getSalt()));
		
		$test->setEmail("test.ukandoit@gmail.com");
		$test->setEmailCanonical("test.ukandoit@gmail.com");
		$test->setEnabled(true);
		$test->setSuperAdmin(true);
		$manager->persist($test);

		$activity = new Activity();
		$activity->setName("course");
		$manager->persist($activity);

		$challenge = new Challenge();
		$challenge->setTitle("challenge");
		$challenge->setCreationDate(new \DateTime("2016-02-01"));
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

		$challenge3 = new Challenge();
		$challenge3->setTitle("challenge3");
		$challenge3->setCreationDate(new \DateTime("2016-01-25"));
		$challenge3->setEndDate(new \DateTime("2016-03-01"));
		$challenge3->setCreator($test);
		$challenge3->setActivity($activity);
		$manager->persist($challenge3);

		$challenge4 = new Challenge();
		$challenge4->setTitle("challenge4");
		$challenge4->setEndDate(new \DateTime("2016-02-14"));
		$challenge4->setCreator($admin);
		$challenge4->setActivity($activity);
		$manager->persist($challenge4);

		$challenge5 = new Challenge();
		$challenge5->setTitle("challenge5");
		$challenge5->setEndDate(new \DateTime("2016-02-29"));
		$challenge5->setCreator($admin);
		$challenge5->setActivity($activity);
		$manager->persist($challenge5);

		$challenge6 = new Challenge();
		$challenge6->setTitle("challenge6");
		$challenge6->setEndDate(new \DateTime("2016-02-21"));
		$challenge6->setCreator($admin);
		$challenge6->setActivity($activity);
		$manager->persist($challenge6);

		$challenge7 = new Challenge();
		$challenge7->setTitle("challenge7");
		$challenge7->setCreationDate(new \DateTime("2016-02-04"));
		$challenge7->setEndDate(new \DateTime("2016-03-15"));
		$challenge7->setCreator($test);
		$challenge7->setActivity($activity);
		$manager->persist($challenge7);

		$challenge8 = new Challenge();
		$challenge8->setTitle("challenge8");
		$challenge8->setEndDate(new \DateTime("2016-02-14"));
		$challenge8->setCreator($admin);
		$challenge8->setActivity($activity);
		$manager->persist($challenge8);

		$challenge9 = new Challenge();
		$challenge9->setTitle("challenge9");
		$challenge9->setEndDate(new \DateTime("2016-02-25"));
		$challenge9->setCreator($admin);
		$challenge9->setActivity($activity);
		$manager->persist($challenge9);

		$challenge10 = new Challenge();
		$challenge10->setTitle("challenge10");
		$challenge10->setEndDate(new \DateTime("2016-02-27"));
		$challenge10->setCreator($test);
		$challenge10->setActivity($activity);
		$manager->persist($challenge10);

		$manager->flush();
	}
}
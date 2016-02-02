<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\DeviceType;
use AppBundle\Entity\User;
use AppBundle\Entity\Level;

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

		$manager->flush();
	}
}
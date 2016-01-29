<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\DeviceType;
use AppBundle\Entity\User;

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

		$jawbone_up_24 = new DeviceType();
		$jawbone_up_24->setName("Jawbone UP 24");

		$admin = new User();
		$admin->setUsername("admin");

		$encoder = $this->container
			->get('security.encoder_factory')
			->getEncoder($admin)
			;
		$admin->setPassword($encoder->encodePassword('admin', $admin->getSalt()));
		
		$admin->setEmail("contact.ukandoit@gmail.com");
		$admin->setEnabled(true);
		$admin->setSuperAdmin(true);

		$manager->persist($withings_activite_pop);
		$manager->persist($jawbone_up_24);
		$manager->persist($admin);
		$manager->flush();
	}
}
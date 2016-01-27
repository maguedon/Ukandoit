<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Form\NewPossessedDeviceType;
use AppBundle\Entity\PossessedDevice;

/**
     * @Route("/user", name="user")
     */
class DefaultController extends Controller
{

	/**
     * @Route("/object/new", name="new_object")
     */
	public function addObject(){
		$possessedDevice = new PossessedDevice();
		$form = $this->createForm(NewPossessedDeviceType::class, $possessedDevice);

		return $this->render("UserBundle:Default:addObject.html.twig", array(
			'form' => $form->createView()
			));
	}
}

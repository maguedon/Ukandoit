<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Form\NewPossessedDeviceType;
use AppBundle\Entity\PossessedDevice;
use Symfony\Component\HttpFoundation\Request;

/**
     * @Route("/user", name="user")
     */
class DefaultController extends Controller
{

	/**
     * @Route("/object/new", name="new_object")
     */
	public function addObject(Request $request){
		$possessedDevice = new PossessedDevice();
		$form = $this->createForm(NewPossessedDeviceType::class, $possessedDevice);

		$current_user = $this->container->get('security.context')->getToken()->getUser();

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			if(strpos($possessedDevice->getDeviceType()->getName(), "Withings")){

			}
			// Jawbone
			else{
				$em = $this->get('doctrine')->getManager();

				$possessedDevice->setUser($current_user);

				$em->persist($possessedDevice);
				$em->flush();

				$jawbone = $this->get("app.jawbone");
				$url = $jawbone->connection();
				return $this->redirect($url);
			}
		}

		return $this->render("UserBundle:Default:addObject.html.twig", array(
			'form' => $form->createView()
			));
	}

	/**
     * @Route("/jawbone/token", name="jawbone_token")
     */
	public function getJawboneTokenAction(){
		$jawbone = $this->get("app.jawbone");
		$json = $jawbone->getToken();

		$current_user = $this->container->get('security.context')->getToken()->getUser();
		$current_user->getLastPossessedDevice()->setAccessTokenJawbone($json["access_token"]);

		$em = $this->get('doctrine')->getManager();
		$em->flush();

		return $this->redirectToRoute("homepage");
	}
}

<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/user", name="user")
 */
class DefaultController extends Controller
{
	/**
     * @Route("/delete", name="user_delete")
     */
	public function deleteUserAction(){
		$user = $this->container->get('security.context')->getToken()->getUser();
		$userManager = $this->container->get('fos_user.user_manager');
		$userManager->deleteUser($user);
		$this->get('session')->getFlashBag()->add('message', $user->getUsername() . ' : Votre compte a été supprimé');
		return $this->redirect($this->generateUrl('homepage'));

	}

	/**
     * @Route("/{name}", name="user_other")
     */
	public function showOtherAction($name){
		$userManager = $this->container->get('fos_user.user_manager');
 		$user = $userManager->findUserByUsername($name);

		return $this->container->get('templating')->renderResponse('FOSUserBundle:Profile:show_other.html.twig', array(
            'user' => $user
        ));

	}
}

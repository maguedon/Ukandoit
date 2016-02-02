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
}

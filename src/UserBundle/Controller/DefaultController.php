<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\PossessedDevice;
use AppBundle\Form\NewPossessedDeviceType;

class DefaultController extends Controller
{
	/**
     * @Route("/user/delete", name="user_delete")
     */
	public function deleteUserAction(){
		// Si on n'est pas connecté on redirige vers login
        $current_user = $this->container->get('security.context')->getToken()->getUser();
        if($current_user == "anon.")
            return $this->redirectToRoute('fos_user_security_login');
        
        $userManager = $this->container->get('fos_user.user_manager');
        $userManager->deleteUser($current_user);
        $this->get('session')->getFlashBag()->add('message', $current_user->getUsername() . ' : Votre compte a été supprimé');
        return $this->redirect($this->generateUrl('homepage'));

    }


    /**
     * @Route("/user/challenges", name="my_challenges")
     */
    public function myChallengesAction(){
        // Si on n'est pas connecté on redirige vers login
        $current_user = $this->container->get('security.context')->getToken()->getUser();
        if($current_user == "anon.")
            return $this->redirectToRoute('fos_user_security_login');

        $challengeService = $this->get('app.challenges');
        $finishedChallenges = $challengeService->getFinishedChallenges($current_user);
        $challengesAccepted = $challengeService->getNotFinishedChallenges($current_user);

       return $this->render('UserBundle:Profile:my_challenges.html.twig', array(
        'finishedChallenges' => $finishedChallenges,
        'challengesAccepted' => $challengesAccepted
        ));
   }

    /**
     * @Route("/user/objects", name="objects")
     */
    public function objectsAction(Request $request){
        // Si on n'est pas connecté on redirige vers login
        $current_user = $this->container->get('security.context')->getToken()->getUser();
        if($current_user == "anon.")
            return $this->redirectToRoute('fos_user_security_login');

        $possessedDevice = new PossessedDevice();
        $form = $this->createForm(NewPossessedDeviceType::class, $possessedDevice);

        $form->handleRequest($request);
        $possessedDevice->setUser($current_user);

        if ($form->isSubmitted() && $form->isValid()) {
            //On regarde si l'objet existe déjà
            $already_exist = false;
            foreach($current_user->getPossessedDevices() as $device){
                if($device->getDeviceType() == $possessedDevice->getDeviceType()){
                    $already_exist = true;
                    break;
                }
            }

            //Si l'objet n'existe pas, on le crée
            if(!$already_exist){
            // Enregistrement de l'objet
                $em = $this->get('doctrine')->getManager();
                $em->persist($possessedDevice);
                $em->flush();

                if($possessedDevice->getDeviceType()->getName() == "Withings Activité Pop"){
                    $withings = $this->get("app.withings");
                    $withings->connection();
                    //return $this->redirectToRoute('withings_token');
                }
                elseif ($possessedDevice->getDeviceType()->getName() == "Jawbone UP 24"){
                    $jawbone = $this->get("app.jawbone");
                    $url = $jawbone->connection();
                    return $this->redirect($url);
                }
                elseif ($possessedDevice->getDeviceType()->getName() == "Google Fitness"){
                    $google = $this->get("app.googlefit");
                    $url = $google->connection();
                    return $this->redirect($url);
                }
            }
            else{
                $this->setFlash("message", "Vous avez déjà un objet " . $possessedDevice->getDeviceType()->getName());
            }

        }
        return $this->render("UserBundle:Profile:objects.html.twig", array(
            'form' => $form->createView()
            ));
        
    }

    /**
     * @Route("/user/objects/delete/{id}", name="objects_delete")
     */
    public function deleteObjectAction($id){
        $em = $this->get('doctrine')->getManager();
        $object = $em->getRepository("AppBundle:PossessedDevice")->find($id);
        $em->remove($object);
        $em->flush();

        $this->setFlash("message", "Objet supprimé !");

        return $this->redirectToRoute('objects');
    }

	/**
     * @Route("/user/{name}", name="user_other")
     */
	public function showOtherAction($name){
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($name);

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Profile:show_other.html.twig', array(
            'user' => $user
            ));

    }

   /**
     * @param string $action
     * @param string $value
     */
   protected function setFlash($action, $value)
   {
    $this->container->get('session')->getFlashBag()->set($action, $value);
}

}

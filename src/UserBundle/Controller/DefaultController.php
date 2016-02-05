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
        $userManager->deleteUser($user);
        $this->get('session')->getFlashBag()->add('message', $user->getUsername() . ' : Votre compte a été supprimé');
        return $this->redirect($this->generateUrl('homepage'));

    }


       /**
     * @Route("/user/challenges", name="my_challenges")
     */
       public function myChallengesAction(){
         $current_user = $this->container->get('security.context')->getToken()->getUser();        
         $challengesCreated = $current_user->getChallengesCreated();
         $challengesAccepted = $current_user->getChallengesAccepted();        
         return $this->render('UserBundle:Profile:my_challenges.html.twig', array(
           "challenges" => $challengesCreated,
           "challengesAccepted" => $challengesAccepted,
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
            // Enregistrement de l'objet
            $em = $this->get('doctrine')->getManager();
            $em->persist($possessedDevice);
            $em->flush();

            if($possessedDevice->getDeviceType()->getName() == "Withings Activité Pop"){
                $withings = $this->get("app.withings");
                $withings->connection();
                //return $this->redirectToRoute('withings_token');
            }
            // Jawbone
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
        return $this->render("UserBundle:Profile:objects.html.twig", array(
            'form' => $form->createView()
            ));
        
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

}

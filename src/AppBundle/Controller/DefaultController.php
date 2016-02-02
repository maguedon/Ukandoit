<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DeviceType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\NewPossessedDeviceType;
use AppBundle\Entity\PossessedDevice;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(){
        return $this->render('AppBundle:Default:index.html.twig', array(
            "url"=>"accueil"
            ));
    }
      /**
     * @Route("/apropos", name="about")
     */
    public function aboutAction(){
        return $this->render('AppBundle:Default:about.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contactAction(Request $request){


    $form = $this->createFormBuilder()
        ->add('name', TextType::class)
        ->add('email', EmailType::class)
        ->add('subject', TextType::class)
        ->add('message', TextareaType::class)
        ->add('send', SubmitType::class)
        ->getForm();

    $form->handleRequest($request);

    if ($form->isValid()) {
        // data is an array with "name", "email", and "message" keys
        $data = $form->getData();


        $message = \Swift_Message::newInstance()
        ->setSubject($data['subject'])
        ->setFrom($data['email'])
        ->setBody("Vous avez reçu un message de " .$data['name'] . " avec l'adresse : " . $data['name'] .  "\ncontenu de message : \n"  . $data['message']);
        $this->get('mailer')->send($message);

      //  $this->get('session')->setFlash('blogger-notice', 'Your contact enquiry was successfully sent. Thank you!');

        // Redirect - This is important to prevent users re-posting
        // the form if they refresh the page
       
        return $this->render('AppBundle:Default:contact.html.twig', array(
           "form"=>$form->createView()
            ));

    }


        return $this->render('AppBundle:Default:contact.html.twig', array(
           "form"=>$form->createView()
            ));
    }

    /**
     * @Route("/mentions-legales", name="legals")
     */
    public function legalsAction(){
        return $this->render('AppBundle:Default:legals.html.twig');
    }

    /**
     * @Route("/withings/", name="withings")
     */
    public function withingsAction(){
        $withings = $this->get("app.withings");
        $withings->connection();

        $current_user = $this->container->get('security.context')->getToken()->getUser();
        $current_user->getLastPossessedDevice();

        $possessedDevice = $current_user->getLastPossessedDevice();
        $possessedDevice->setAccessTokenKeyWithings($withings->getAccessTokenKey());
        $possessedDevice->setAccessTokenSecretWithings($withings->getAccessTokenSecret());
        $possessedDevice->setUserIdWithings($withings->getUserID());

        $em = $this->get('doctrine')->getManager();
        $em->persist($possessedDevice);
        $em->flush();

        return $this->redirectToRoute('homepage');
    }

        /**
     * @Route("/object/new", name="new_object")
     */
    public function addObject(Request $request){
        $possessedDevice = new PossessedDevice();
        $form = $this->createForm(NewPossessedDeviceType::class, $possessedDevice);

        $current_user = $this->container->get('security.context')->getToken()->getUser();

        $form->handleRequest($request);
        $possessedDevice->setUser($current_user);

        if ($form->isSubmitted() && $form->isValid()) {
            if($possessedDevice->getDeviceType()->getName() == "Withings Activité Pop"){
                $em = $this->get('doctrine')->getManager();
                $em->persist($possessedDevice);
                $em->flush();

                return $this->redirectToRoute('withings');
            }
            // Jawbone
            else{
                $em = $this->get('doctrine')->getManager();

                //$possessedDevice->setUser($current_user);

                $em->persist($possessedDevice);
                $em->flush();

                $jawbone = $this->get("app.jawbone");
                $url = $jawbone->connection();
                return $this->redirect($url);
            }
        }

        return $this->render("AppBundle:Default:addObject.html.twig", array(
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

    /**
     * @Route("/jawbone/{id}/moves", name="jawbone_moves")
     */
    public function jawboneMovesAction($id){
        $possessedDevice = $this->getDoctrine()->getRepository('AppBundle:PossessedDevice')->find($id);

        $jawbone = $this->get("app.jawbone");
        $json = $jawbone->getMoves($possessedDevice->getAccessTokenJawbone());

        $hourly_totals = $json['items'][0]['details']['hourly_totals'];

        return $this->render('AppBundle:Default:jawboneMoves.html.twig', array(
            'hourly_totals' => $hourly_totals
            ));
    }

    /**
     * @Route("/withings/{id}/moves", name="withings_moves")
     */
    public function withingsMovesAction($id){
        $possessedDevice = $this->getDoctrine()->getRepository('AppBundle:PossessedDevice')->find($id);

        $jawbone = $this->get("app.withings");
        $json = $jawbone->getMoves($possessedDevice->getAccessTokenWithings());

        $hourly_totals = $json['items'][0]['details']['hourly_totals'];

        return $this->render('AppBundle:Default:withingsMoves.html.twig', array(
            'hourly_totals' => $hourly_totals
            ));
    }

    // A deplacer dans le bundle user ?

   /**
     * @Route("/defis", name="challenges")
     */
    public function challengesAction(){
        return $this->render('AppBundle:Default:challenges.html.twig');
    }
}


<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DeviceType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\NewPossessedDeviceType;
use AppBundle\Form\NewChallengeType;
use AppBundle\Entity\Challenge;
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
         $challenges = $this->getDoctrine()->getRepository('AppBundle:Challenge')->findAll();

        return $this->render('AppBundle:Default:index.html.twig', array(
            "url"=>"accueil",
            "challenges" => $challenges

            ));
    }

    /**
     * @Route("/add_defis", name="add_defis")
     */
      public function addDefisAction(Request $request){

        $challenge = new Challenge();

        $form = $this->createForm(NewChallengeType::class, $challenge);
        $form->handleRequest($request);

        $current_user = $this->container->get('security.context')->getToken()->getUser();

        if ($form->isSubmitted() && $form->isValid()) {

            $challenge->setCreator($current_user);

            // Enregistrement de l'objet
            $em = $this->get('doctrine')->getManager();
            $em->persist($challenge);
            $em->flush();

            //     if($challenge->getDeviceType()->getName() == "Withings Activité Pop"){
            //         return $this->redirectToRoute('withings');
            //     }
            //     // Jawbone
            //     else{
            //         $jawbone = $this->get("app.jawbone");
            //         $url = $jawbone->connection();
            //         return $this->redirect($url);
            //     }
        }

        return $this->render("AppBundle:Default:add_defis.html.twig", array(
            'form' => $form->createView()
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

        if ($form->isValid() && $_POST['g-recaptcha-response']!="") {
        // data is an array with "name", "email", and "message" keys
        $data = $form->getData();

        

        $message = \Swift_Message::newInstance()
        ->setSubject($data['subject'] . " Mail envoyé depuis Ukando'it")
        ->setFrom($data['email'])
        ->setTo('contact.ukandoit@gmail.com')
        ->setBody("Vous avez reçu un message de " .$data['name'] . " avec l'adresse : " . $data['email'] .  "\ncontenu de message : \n"  . $data['message']);
        $this->get('mailer')->send($message);


      //  $this->get('session')->setFlash('blogger-notice', 'Your contact enquiry was successfully sent. Thank you!');
            $this->setFlash('message', 'Votre mail a bien été envoyé.');
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

//    /**
//     * @Route("/withings/", name="withings")
//     */
//    public function withingsAction(){
//        $em = $this->get('doctrine')->getManager();
//        $withings = $this->get("app.withings");
//        $withings->connection();
//        var_dump($withings->getAccessTokenKey());
//        var_dump($withings->getAccessTokenSecret());
//        var_dump($withings->getUserID());
//
//        $current_user = $this->container->get('security.context')->getToken()->getUser();
//
//        $possessedDevice = $current_user->getLastPossessedDevice();
//        $possessedDevice->setAccessTokenKeyWithings($withings->getAccessTokenKey());
//        $possessedDevice->setAccessTokenSecretWithings($withings->getAccessTokenSecret());
//        $possessedDevice->setUserIdWithings($withings->getUserID());
//
//        if ($withings->getAccessTokenKey() == null || $withings->getAccessTokenSecret() == null || $withings->getUserID() == null){
//            $em->remove($possessedDevice);
//            $em->flush();
//        }
//        else{
//          //  $em = $this->get('doctrine')->getManager();
//            $em->persist($possessedDevice);
//            $em->flush();
//        }
//
//       // return $this->redirectToRoute('objects');
//    }

    /**
     * @Route("/withings/token", name="withings_token")
     */
    public function getWithingsTokenAction(){
        $current_user = $this->container->get('security.context')->getToken()->getUser();
        $possessedDevice = $current_user->getLastPossessedDevice();
        $em = $this->get('doctrine')->getManager();

        $withings = $this->get("app.withings");
        $withings->getToken();

        if ($withings->getAccessTokenKey() == null || $withings->getAccessTokenSecret() == null || $withings->getUserID() == null){
            $em->remove($possessedDevice);
            $em->flush();
        }
        else{
            $possessedDevice->setAccessTokenKeyWithings($withings->getAccessTokenKey());
            $possessedDevice->setAccessTokenSecretWithings($withings->getAccessTokenSecret());
            $possessedDevice->setUserIdWithings($withings->getUserID());
            $em->persist($possessedDevice);
            $em->flush();
        }
        return $this->redirectToRoute('objects');
    }

    /**
     * @Route("/objects", name="objects")
     */
    public function objectsAction(Request $request){
        $possessedDevice = new PossessedDevice();
        $form = $this->createForm(NewPossessedDeviceType::class, $possessedDevice);

        $current_user = $this->container->get('security.context')->getToken()->getUser();

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
            else{
                $jawbone = $this->get("app.jawbone");
                $url = $jawbone->connection();
                return $this->redirect($url);
            }
        }
        return $this->render("AppBundle:Default:objects.html.twig", array(
            'form' => $form->createView()
            ));
    }

    /**
     * @Route("/jawbone/token", name="jawbone_token")
     */
    public function getJawboneTokenAction(){
        $current_user = $this->container->get('security.context')->getToken()->getUser();
        $jawbone = $this->get("app.jawbone");

        if ($jawbone->getToken()){
            $json = $jawbone->getToken();

            $current_user->getLastPossessedDevice()->setAccessTokenJawbone($json["access_token"]);
            $em = $this->get('doctrine')->getManager();
            $em->flush();
            return $this->redirectToRoute("objects");
        }

        $em = $this->get('doctrine')->getManager();
        $em->remove($current_user->getLastPossessedDevice());
        $em->flush();

        return $this->redirectToRoute("objects");
    }

    /**
     * @Route("/jawbone/{id}/moves", name="jawbone_moves")
     */
    public function jawboneMovesAction($id){
        $possessedDevice = $this->getDoctrine()->getRepository('AppBundle:PossessedDevice')->find($id);

        $jawbone = $this->get("app.jawbone");
        $json = $jawbone->getMoves($possessedDevice->getAccessTokenJawbone());

        if (count($json['items']) != 0)
            $hourly_totals = $json['items'][0]['details']['hourly_totals'];
        else
            $hourly_totals = $json['items'];

        return $this->render('AppBundle:Default:jawboneMoves.html.twig', array(
            'hourly_totals' => $hourly_totals,
            'json' => $json
            ));
    }

    /**
     * @Route("/withings/{id}/moves", name="withings_moves")
     */
    public function withingsMovesAction($id){
        $possessedDevice = $this->getDoctrine()->getRepository('AppBundle:PossessedDevice')->find($id);

        $withings = $this->get("app.withings");
        $withings->authenticate($possessedDevice);

        $json = $withings->getActivities($withings->getUserID() , "2016-02-01"); //,"2016-01-25"
       // $intra = $withings->getIntradayActivities($withings->getUserID() , "2016-02-01 8:00:00", "2016-02-01 18:00:00");
       // var_dump($intra);

       return $this->render('AppBundle:Default:withingsMoves.html.twig', array(
            'activities' => $json["body"]["activities"]
            ));
    }

    // A deplacer dans le bundle user ?

   /**
     * @Route("/defis", name="challenges")
     */
    public function challengesAction(){
        $challenges = $this->getDoctrine()->getRepository('AppBundle:Challenge')->findAll();
        
        $userManager = $this->container->get('fos_user.user_manager');

        foreach ($challenges as $value) {
            $user = $userManager->findUserByUsername($value->getCreator());
            $levelUser = $user->getLevel();
        }
        return $this->render('AppBundle:Default:challenges.html.twig', array(
            "challenges" => $challenges
        ));
    }

    /**
     * @Route("/defis/{challenge}/{device}/accepted", name="accepted")
     */
    public function challengesAcceptedAction($challenge, $device){
        $current_user = $this->container->get('security.context')->getToken()->getUser();
        $challenge = $this->getDoctrine()->getRepository('AppBundle:Challenge')->find($challenge);
        $possessedDevice = $this->getDoctrine()->getRepository('AppBundle:PossessedDevice')->find($device);
        

        $challenge->addChallenger($current_user);
        $current_user->addChallengesAccepted($challenge);
        // AJOUTER OBJECT !!!!


        $em = $this->get('doctrine')->getManager();
        $em->persist($challenge);
        $em->persist($current_user);
        //$em->persist($device);
        $em->flush();
        return $this->redirectToRoute("defis");


        return $this->render('AppBundle:Default:challenges.html.twig', array(
            "challenge" => $challenge
        ));
    }
     protected function setFlash($action, $value)
    {
        $this->container->get('session')->getFlashBag()->set($action, $value);
    }

    protected function getEngine()
    {
        return $this->container->getParameter('fos_user.template.engine');
    }




}


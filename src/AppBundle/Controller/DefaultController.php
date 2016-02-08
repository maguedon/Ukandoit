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
use AppBundle\Entity\User_Challenge;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\DateTime;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(){

        $em = $this->get('doctrine')->getManager();
        $challengesService = $this->get('app.challenges');
        $lastChallenges = $challengesService->getLastChallenges();
        $bestChallenges = $challengesService->getBestChallenges();
        $bestChallengers = $em->getRepository('AppBundle:User')->findBests();
        return $this->render('AppBundle:Default:index.html.twig', array(
            "url"=>"accueil",
            "lastChallenges" => $lastChallenges,
            "bestChallenges" => $bestChallenges,
            "bestChallengers" => $bestChallengers
            ));
    }

    /**
     * @Route("/add_defis", name="add_defis")
     */
    public function addDefisAction(Request $request){
        // Si on n'est pas connecté on redirige vers login
        $current_user = $this->container->get('security.context')->getToken()->getUser();
        if($current_user == "anon.")
            return $this->redirectToRoute('fos_user_security_login');

        $challenge = new Challenge();

        $form = $this->createForm(NewChallengeType::class, $challenge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $challenge->setCreator($current_user);

            // Enregistrement de l'objet
            $em = $this->get('doctrine')->getManager();
            $em->persist($challenge);
            $em->flush();
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

    /**
     * @Route("/withings/token", name="withings_token")
     */
    public function getWithingsTokenAction(){
        // Si on n'est pas connecté on redirige vers login
        $current_user = $this->container->get('security.context')->getToken()->getUser();
        if($current_user == "anon.")
            return $this->redirectToRoute('fos_user_security_login');

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
     * @Route("/jawbone/token", name="jawbone_token")
     */
    public function getJawboneTokenAction(){
        // Si on n'est pas connecté on redirige vers login
        $current_user = $this->container->get('security.context')->getToken()->getUser();
        if($current_user == "anon.")
            return $this->redirectToRoute('fos_user_security_login');

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
     * @Route("/google/token", name="google_token")
     */
    public function getGoogleTokenAction(){
        // Si on n'est pas connecté on redirige vers login
        $current_user = $this->container->get('security.context')->getToken()->getUser();
        if($current_user == "anon.")
            return $this->redirectToRoute('fos_user_security_login');

        $possessedDevice = $current_user->getLastPossessedDevice();
        $em = $this->get('doctrine')->getManager();

        $google = $this->get("app.googlefit");
        $code = $google->getToken();

        if ($code == false || $google->getAccessToken() == null || $google->getRefreshToken() == null){
            $em->remove($possessedDevice);
            $em->flush();
            return $this->redirectToRoute('objects');
        }
        else{
            $possessedDevice->setAccessTokenGoogle($google->getAccessToken());
            $possessedDevice->setRefreshTokenGoogle($google->getRefreshToken());
            $em->persist($possessedDevice);
            $em->flush();
        }
        return $this->redirectToRoute('objects');
    }

    /**
     * @Route("/google/{id}/moves", name="google_moves")
     */
    public function googleMovesAction($id){
        // Si on n'est pas connecté on redirige vers login
        $current_user = $this->container->get('security.context')->getToken()->getUser();
        $possessedDevice = $this->getDoctrine()->getRepository('AppBundle:PossessedDevice')->find($id);
        if($current_user == "anon.")
            return $this->redirectToRoute('fos_user_security_login');
        if($possessedDevice == null)
            return $this->redirectToRoute('objects'); //possessedDevice ID n'existe pas !

        $google = $this->get("app.googlefit");
        $updated = $google->authenticate($possessedDevice);
        if ($updated == true){
            $em = $this->get('doctrine')->getManager();
            $em->persist($possessedDevice);
            $em->flush();
        }

        $json = $google->getActivities(/*USERID ,*/ "2016-02-01"); //,"2016-01-25"

        return $this->render('AppBundle:Default:withingsMoves.html.twig', array(
            'activities' => $json["body"]["activities"]
            ));
    }
    /**
     * @Route("/jawbone/{id}/moves", name="jawbone_moves")
     */
    public function jawboneMovesAction($id){
        // Si on n'est pas connecté on redirige vers login
        $current_user = $this->container->get('security.context')->getToken()->getUser();
        $possessedDevice = $this->getDoctrine()->getRepository('AppBundle:PossessedDevice')->find($id);

        if($current_user == "anon.")
            return $this->redirectToRoute('fos_user_security_login');
        if($possessedDevice == null)
            return $this->redirectToRoute('objects'); //possessedDevice ID n'existe pas !

        $jawbone = $this->get("app.jawbone");
        $json = $jawbone->getMoves($possessedDevice->getAccessTokenJawbone(), "2016-01-21 00:00:00", "2016-01-23 00:00:00");

        if (count($json['items']) != 0)
            $hourly_totals = $json['items'][0]['details']['hourly_totals'];
        else
            $hourly_totals = $json['items'];

        $standart = $jawbone->standardizeJSON($json);

        return $this->render('AppBundle:Default:jawboneMoves.html.twig', array(
            'hourly_totals' => $hourly_totals,
            'standart' => $standart,
            'json' => $json
            ));
    }

    /**
     * @Route("/withings/{id}/moves", name="withings_moves")
     */
    public function withingsMovesAction($id){
        // Si on n'est pas connecté on redirige vers login
        $current_user = $this->container->get('security.context')->getToken()->getUser();
        $possessedDevice = $this->getDoctrine()->getRepository('AppBundle:PossessedDevice')->find($id);
        if($current_user == "anon.")
            return $this->redirectToRoute('fos_user_security_login');
        if($possessedDevice == null)
            return $this->redirectToRoute('objects');

        $withings = $this->get("app.withings");
        $withings->authenticate($possessedDevice);

        $json = $withings->getActivities($withings->getUserID() , "2015-10-31", "2015-11-07"); //,"2016-01-25"

       // $intra = $withings->getIntradayActivities($withings->getUserID() , "2016-02-01 8:00:00", "2016-02-01 18:00:00");
       // var_dump($intra);
        $standart = $withings->standardizeJSON($json);

        return $this->render('AppBundle:Default:withingsMoves.html.twig', array(
            'activities' => $json["body"]["activities"],
            'standart' => $standart
            ));
    }
    // A deplacer dans le bundle user ?

   /**
     * @Route("/defis", name="challenges")
     */
   public function challengesAction(){
    $challenges = $this->getDoctrine()->getRepository('AppBundle:Challenge')->findBy(
                   array(),        // $where
                   array('creationDate' => 'DESC'),    // $orderBy
                   5,                        // $limit
                   0                          // $offset
                   );

    $allChallenges = $this->getDoctrine()->getRepository('AppBundle:Challenge')->findAll();
    $nbAllChallenges = count($allChallenges);

    $userManager = $this->container->get('fos_user.user_manager');

    foreach ($challenges as $value) {
        $user = $userManager->findUserByUsername($value->getCreator());
        $levelUser = $user->getLevel();
    }
    return $this->render('AppBundle:Default:challenges.html.twig', array(
        "challenges" => $challenges,
        "nbChallenges" => $nbAllChallenges
        ));
}

    /**
     * @Route("/defisajaxdonttouch", name="defisAjax")
     */
    public function getAjaxChallengeAction()
    {
        $request = $this->container->get('request');

        if($request->isXmlHttpRequest())
        {
            $lastID = $request->query->get('last');

            $challenges = $this->getDoctrine()->getRepository('AppBundle:Challenge')->findByLowerId($lastID);

            //Version résultat SQL
            $finalChallenges = array();
            foreach($challenges as $challenge){
                $current_challenge = $this->getDoctrine()->getRepository('AppBundle:Challenge')->find($challenge['id']);
                array_push($finalChallenges, $current_challenge);
            }

            $userManager = $this->container->get('fos_user.user_manager');

            foreach ($finalChallenges as $value) {
                $user = $userManager->findUserByUsername($value->getCreator());
                $levelUser = $user->getLevel();
            }

            return $this->render('AppBundle:Ajax:ajax_challenge.html.twig', array(
                "challenges" => $finalChallenges
                ));
        }
    }

    /**
     * @Route("/defis/{challenge}/{device}/accepted", name="accepted")
     */
    public function challengesAcceptedAction($challenge, $device){
        $current_user = $this->container->get('security.context')->getToken()->getUser();
        $challenge = $this->getDoctrine()->getRepository('AppBundle:Challenge')->find($challenge);
        $possessedDevice = $this->getDoctrine()->getRepository('AppBundle:PossessedDevice')->find($device);

        $this->setFlash('message', 'Vous avez relevé le défi de '.$challenge->getCreator()->getUsername());

        // AJOUTER OBJECT !!!!
        $user_challenge = new user_challenge();
        $user_challenge->setDeviceUsed($possessedDevice);
        $user_challenge->setChallenger($current_user);
        $user_challenge->setChallenge($challenge);


        $em = $this->get('doctrine')->getManager();
        $em->persist($user_challenge);
        //$em->persist($device);
        $em->flush();
        return $this->redirectToRoute("challenges");


         /*   return $this->render('AppBundle:Default:challenges.html.twig', array(
            "challenge" => $challenge
            ));*/
    }

    /**
     * @Route("/defis/{challenge}/", name="showChallenge")
     */
    public function showCurrentChallenge($challenge){
        $current_user = $this->container->get('security.context')->getToken()->getUser();
        $challenge = $this->getDoctrine()->getRepository('AppBundle:Challenge')->find($challenge);

        $collection = $challenge->getUserChallenges();

        //$ukandoit = $this->get("app.ukandoit");
        //$ukandoit->getDataFromAPI($devise, $challenge);


        $this->getDoctrine()->getRepository('AppBundle:PossessedDevice')->find();
        //$devise  = $current_user->get



       // if ($challenge->)

        /*   return $this->render('AppBundle:Default:challenges.html.twig', array(
           "challenge" => $challenge
           ));*/
        return $this->render('AppBundle:Default:show_challenge.html.twig', array(
            "user" => $current_user,
            "collection" => $collection
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


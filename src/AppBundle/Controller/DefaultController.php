<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use AppBundle\Entity\DeviceType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\NewPossessedDeviceType;
use AppBundle\Form\NewChallengeType;
use AppBundle\Entity\Challenge;
use AppBundle\Entity\PossessedDevice;
use AppBundle\Entity\User_Challenge;
use AppBundle\Entity\Level;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\DateTime;

class DefaultController extends Controller
{

        /**
     * Génère le sitemap du site.
     *
     * @Route("/sitemap.{_format}", name="sitemap", Requirements={"_format" = "xml"})
     */
    public function siteMapAction()
    {
        return $this->render(
            'AppBundle:Default:sitemap.xml.twig',
            array('urls' => $this->get('app.sitemap')->generer())
        );
    }
    
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(){
        $em = $this->get('doctrine')->getManager();
        $challengesService = $this->get('app.challenges');
        $lastChallenges = $em->getRepository('AppBundle:Challenge')->findByEndDate(9);
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

        $current_user = $this->container->get('security.context')->getToken()->getUser();
        if($current_user == "anon.")
            return $this->redirectToRoute('fos_user_security_login');

        $data = array();

        $challenge = new Challenge();

        // ------------ CREATION FORM 1 ------------ //
        $formBuilderOne = $this->container
        ->get('form.factory')
        ->createNamedBuilder('formOne', 'form', NULL, array('validation_groups' => array()))
        ->add('endDate', 'date', array(
            'widget' => 'single_text',
            'input' => 'datetime',
            'format' => 'dd/MM/yyyy',
            'attr' => array('class' => 'date')))
        ->add('activity', 'entity', array(
            'class' => 'AppBundle:Activity',
            'property' => 'name'))
        ->add('nbSteps', 'integer', array(
            'required' => false))
        ->add('kilometres', 'number', array(
            'required' => false))
        ->add('time', 'integer')
        ->add('submit', 'submit', array(
            'label' => 'Envoyer'
        ));

        $formOne = $formBuilderOne
        ->getForm()
        ->handleRequest($request);

        // ------------ CREATION FORM 2 ------------ //
        $formBuilderTwo = $this->container
        ->get('form.factory')
        ->createNamedBuilder('formTwo', 'form', NULL, array('validation_groups' => array()))
        ->add('endDate', 'date', array(
            'widget' => 'single_text',
            'input' => 'datetime',
            'format' => 'dd/MM/yyyy',
            'attr' => array('class' => 'date')))
        ->add('activity', 'entity', array(
            'class' => 'AppBundle:Activity',
            'property' => 'name'))
        ->add('nbSteps', 'integer', array(
            'required' => false))
        ->add('kilometres', 'number', array(
            'required' => false))
        ->add('time', 'integer')
        ->add('submit', 'submit', array(
                'label' => 'Envoyer'
            ));

        $formTwo = $formBuilderTwo
        ->getForm()
        ->handleRequest($request);

        // ------------ FORM 1 VALIDATION------------ //
        if ($formOne->isValid())
        {
            $form_data_endDate = $formOne->get("endDate")->getData();
            $form_data_time = $formOne->get("time")->getData();
            $form_data_nbKm = $formOne->get("kilometres")->getData();
            $form_data_nbSteps = $formOne->get("nbSteps")->getData();
            $form_data_activity = $formOne->get("activity")->getData();
            $form_data_possessedDevice = $_POST["possessedDeviceFormOne"];
            $form_data_currentDate = $challenge->getCreationDate();

            if($form_data_nbKm != null && $form_data_nbKm != 0 && $form_data_nbKm != "0"){
                $form_data_title = "Objectif ".$form_data_nbKm." km en ".$form_data_time. " jour(s)";
            }
            else if ($form_data_nbSteps != null && $form_data_nbSteps != 0 && $form_data_nbSteps != "0"){
                $form_data_title = "Objectif ".$form_data_nbSteps." pas en ".$form_data_time. " jour(s)";
            }

            $avoid_error = $form_data_time ;
            if($avoid_error < 0){
                $avoid_error = 0;
            }

            $now_temp = clone $form_data_currentDate;
            $form_data_endDate_limit = $form_data_currentDate->add(new \DateInterval('P'.$avoid_error.'D'));

            if(($form_data_time <= 0) ||
                ($form_data_endDate_limit->format('Y-m-d') > $form_data_endDate->format('Y-m-d')) ||
                ($form_data_endDate->format('Y-m-d') < $now_temp->format('Y-m-d'))){

                $data["errors"][] = "Erreur, vérifiez les dates renseignées";

            }
            else{
                $challenge->setTitle($form_data_title);
                $challenge->setEndDate($form_data_endDate);
                $challenge->setCreator($current_user);
                $challenge->setActivity($form_data_activity);
                $challenge->setTime($form_data_time);
                $challenge->setNbSteps($form_data_nbSteps);
                $challenge->setKilometres($form_data_nbKm);

                $challenge->setNbPointsFirst(1);
                $challenge->setNbPointsSecond(1);
                $challenge->setNbPointsThird(1);

                $possessedDevice = $this->getDoctrine()->getRepository('AppBundle:PossessedDevice')->find($form_data_possessedDevice);

                $user_challenge = new user_challenge();
                $user_challenge->setDeviceUsed($possessedDevice);
                $user_challenge->setChallenger($current_user);
                $user_challenge->setChallenge($challenge);

                        // Enregistrement de l'objet
                $em = $this->get('doctrine')->getManager();
                $em->persist($challenge);
                $em->persist($user_challenge);
                $em->flush();
            }
        }

        // ------------ FORM 2 VALIDATION ------------ //
        if ($formTwo->isValid())
        {
            $form_data_title = "Objectif ";
            $form_data_endDate = $formTwo->get("endDate")->getData();
            $form_data_time = $formTwo->get("time")->getData();
            $form_data_nbKm = $formTwo->get("kilometres")->getData();
            $form_data_nbSteps = $formTwo->get("nbSteps")->getData();
            $form_data_activity = $formTwo->get("activity")->getData();
            $form_data_possessedDevice = $_POST["possessedDeviceFormTwo"];
            $form_data_currentDate = $challenge->getCreationDate();

            if($form_data_nbKm != null && $form_data_nbKm != 0 && $form_data_nbKm != "0"){
                $form_data_title = "Objectif ".$form_data_nbKm." km en ".$form_data_time. " jour(s)";
            }
            else if ($form_data_nbSteps != null && $form_data_nbSteps != 0 && $form_data_nbSteps != "0"){
                $form_data_title = "Objectif ".$form_data_nbSteps." pas en ".$form_data_time. " jour(s)";
            }

            $avoid_error = $form_data_time ;
            if($avoid_error < 0){
                $avoid_error = 0;
            }

            $now_temp = clone $form_data_currentDate;
            $form_data_endDate_limit = $form_data_currentDate->add(new \DateInterval('P'.$avoid_error.'D'));

            if(($form_data_time <= 0) ||
                ($form_data_endDate_limit->format('Y-m-d') > $form_data_endDate->format('Y-m-d')) ||
                ($form_data_endDate->format('Y-m-d') < $now_temp->format('Y-m-d'))){

                $data["errors"][] = "Erreur, vérifiez les dates renseignées";

            }
            else{
                $challenge->setTitle($form_data_title);
                $challenge->setEndDate($form_data_endDate);
                $challenge->setCreator($current_user);
                $challenge->setActivity($form_data_activity);
                $challenge->setTime($form_data_time);
                $challenge->setNbSteps($form_data_nbSteps);
                $challenge->setKilometres($form_data_nbKm);

                $challenge->setNbPointsFirst(1);
                $challenge->setNbPointsSecond(1);
                $challenge->setNbPointsThird(1);

                $possessedDevice = $this->getDoctrine()->getRepository('AppBundle:PossessedDevice')->find($form_data_possessedDevice);

                $user_challenge = new user_challenge();
                $user_challenge->setDeviceUsed($possessedDevice);
                $user_challenge->setChallenger($current_user);
                $user_challenge->setChallenge($challenge);

                // Enregistrement de l'objet
                $em = $this->get('doctrine')->getManager();
                $em->persist($challenge);
                $em->persist($user_challenge);
                $em->flush();
            }
        }

    return $this->render("AppBundle:Default:add_defis.html.twig", array(
        'formOne' => $formOne->createView(),
        'formTwo' => $formTwo->createView(),
        'data' => $data
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

            $data = $form->getData();

            $message = \Swift_Message::newInstance()
            ->setSubject($data['subject'] . " Mail envoyé depuis Ukando'it")
            ->setFrom($data['email'])
            ->setTo('contact.ukandoit@gmail.com')
            ->setBody("Vous avez reçu un message de " .$data['name'] . " avec l'adresse : " . $data['email'] .  "\ncontenu de message : \n"  . $data['message']);
            $this->get('mailer')->send($message);

            $this->setFlash('message', 'Votre mail a bien été envoyé');

            return $this->redirectToRoute('homepage');

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

      //  $standart = $jawbone->standardizeJSON($json);

        return $this->render('AppBundle:Default:jawboneMoves.html.twig', array(
            //'standart' => $standart,
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

        $json = $withings->getActivities($withings->getUserID() , "2016-01-31", "2016-02-07"); //,"2016-01-25"

       // $intra = $withings->getIntradayActivities($withings->getUserID() , "2016-02-01 8:00:00", "2016-02-01 18:00:00");
       // var_dump($intra);
       // $standart = $withings->standardizeJSON($json);

        return $this->render('AppBundle:Default:withingsMoves.html.twig', array(
            'activities' => $json
          //  'standart' => $standart
            ));
    }

    /**
     * @Route("/defis", name="challenges")
     */
    public function challengesAction(){

        $challenges = $this->getDoctrine()->getRepository('AppBundle:Challenge')->findByEndDate(6);

        $allChallenges = $this->getDoctrine()->getRepository('AppBundle:Challenge')->findAll();
        $nbAllChallenges = count($allChallenges);

        return $this->render('AppBundle:Default:challenges.html.twig', array(
            "challenges" => $challenges,
            "nbChallenges" => $nbAllChallenges,

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


        foreach ($current_user->getChallengesAccepted() as $challenge_accepted){
            if ($challenge_accepted->getChallenge()->getId() == $challenge){
                $this->setFlash('message', 'Vous avez déjà relevé ce défi.'); // Couleur et icone à changer !
                return $this->redirectToRoute("challenges");
            }
        }

        $challenge = $this->getDoctrine()->getRepository('AppBundle:Challenge')->find($challenge);
        $possessedDevice = $this->getDoctrine()->getRepository('AppBundle:PossessedDevice')->find($device);

        $this->setFlash('message', 'Vous avez relevé le défi de '.$challenge->getCreator()->getUsername());

        $user_challenge = new user_challenge();
        $user_challenge->setDeviceUsed($possessedDevice);
        $user_challenge->setChallenger($current_user);
        $user_challenge->setChallenge($challenge);


        $em = $this->get('doctrine')->getManager();
        $em->persist($user_challenge);
        //$em->persist($device);
        $em->flush();
        return $this->redirectToRoute("challenges");


    }

    /**
     * @Route("/defi/surrender/{id}", name="surrender")
     */
    public function surrenderChallengeAction($id){
        $em = $this->get('doctrine')->getManager();
        $current_user = $this->container->get('security.context')->getToken()->getUser();
        $challenge = $this->getDoctrine()->getRepository('AppBundle:Challenge')->find($id);
        $user_challenge = $em->getRepository("AppBundle:User_Challenge")->findOneBy(array("challenge" =>$id, "challenger" => $current_user->getId()));

        if($current_user == "anon.")
            return $this->redirectToRoute('fos_user_security_login');

        if($user_challenge !== null && $challenge->getCreator()->getId() !== $current_user->getId()){
            $em->remove($user_challenge);
            $em->flush();
            //$referer = $this->getRequest()->headers->get('referer');
            //return $this->redirectReferer();
            return $this->redirectToRoute('challenges'); //redirriger à la page précédente !
        }
        else
            return $this->redirectToRoute('challenges');
    }

    /**
     * @Route("/defi/delete/{id}", name="delete_challenge")
     */
    public function deleteChallengeAction($id){
        $em = $this->get('doctrine')->getManager();
        $current_user = $this->container->get('security.context')->getToken()->getUser();
        $challenge = $this->getDoctrine()->getRepository('AppBundle:Challenge')->find($id);
        $user_challenge = $em->getRepository("AppBundle:User_Challenge")->findOneBy(array("challenge" =>$id, "challenger" => $current_user->getId()));

        if($challenge->getCreator()->getId() == $current_user->getId() && count($challenge->getUserChallenges()) <= 1){
            $em->remove($user_challenge);
            $em->remove($challenge);
            $em->flush();
            return $this->redirectToRoute('my_challenges');
        }
        else
            return $this->redirectToRoute('my_challenges');
    }

    /**
     * @Route("/defi/{challenge}/", name="showChallenge")
     */
    public function showCurrentChallenge($challenge){
        $challenge = $this->getDoctrine()->getRepository('AppBundle:Challenge')->find($challenge);

        if ( $challenge->getKilometres() !== null )
            $mesure = "mètres";
        else
            $mesure = "pas";

        $challenge_start = $challenge->getCreationDate();
        $challenge_end = $challenge->getEndDate();

        $participants = $challenge->getUserChallenges();

        $result = array();

        foreach ($participants as $collection){
            $id_participant = $collection->getChallenger()->getId();
            $participant = $this->getDoctrine()->getRepository('AppBundle:User')->find($id_participant);

            $avatar_participant = $participant->getAvatar();
            $level_participant = $participant->getLevel();


            $device_participant = $collection->getDeviceUsed();
            $device_participant = $this->getDoctrine()->getRepository('AppBundle:PossessedDevice')->find($device_participant->getId());

            if ($device_participant->getDeviceType()->getName() == "Withings Activité Pop"){
                $withings = $this->get("app.withings");
                $withings->authenticate($device_participant);
                $json = $withings->getActivities($withings->getUserID() , $challenge_start->format("Y-m-d"), $challenge_end->format("Y-m-d"));
            }

            if ($device_participant->getDeviceType()->getName() == "Jawbone UP 24"){
                $jawbone = $this->get("app.jawbone");
                $json = $jawbone->getMoves($device_participant->getAccessTokenJawbone(), $challenge_start->format("Y-m-d"), $challenge_end->format("Y-m-d"));
            }

            $ukandoit = $this->get("app.ukandoit");
            $best_performance = $ukandoit->getDataFromAPI($challenge, $json);

            $data = array(
                "userid" => $id_participant,
                "username" => $participant->getUsername(),
                "avatar" => $avatar_participant,
                "level" => $level_participant,
                "device" => $device_participant->getDeviceType()->getName(),
                "mesure" => $mesure
                );

            $data['performance'] = $best_performance["value"];
            $result[$data['performance']] = $data;
        }

        krsort($result);

        return $this->render('AppBundle:Default:show_challenge.html.twig', array(
            "participants" => $result,
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



// ------------ CONTROLLER AJAX CHARGEMENT INFOS CREATION DEFIS -------------- //

    /**
     * @Route("/adddefisajaxdonttouch", name="addDefisAjax")
     */
    public function getAjaxAddDefisAction()
    {
        $request = $this->container->get('request');

        if($request->isXmlHttpRequest())
        {
            $current_user = $this->container->get('security.context')->getToken()->getUser();

            $id_montre = $request->query->get('montre');

            $date_deb = explode('/', $request->query->get('dateDebPerf'));
            $data['date_deb']  = $date_deb[2].'-'.$date_deb[1].'-'.$date_deb[0];

            $date_fin = explode('/', $request->query->get('dateFinPerf'));
            $data['date_fin'] = $date_fin[2].'-'.$date_fin[1].'-'.$date_fin[0];

            $pas_or_km = $request->query->get('pas_or_km');
            $data['pas_or_km'] = $pas_or_km;

            //Calcule de time
            $time1 = strtotime($data['date_deb']);
            $time2 = strtotime($data['date_fin']);

            if($time2 < $time1){
                $data['error'] = true;
            }
            elseif($data['date_deb'] == $data['date_fin']){
                $data['time'] = 1;
            }
            else{
                $data['time'] = ($time2 - $time1)/(60*60*24);
            }

            $possessedDevice = $this->getDoctrine()->getRepository('AppBundle:PossessedDevice')->find($id_montre);

            $namePossessedDevice = $possessedDevice->getDeviceType()->getName();
            $data['montre']['name'] = $namePossessedDevice;

            switch ($namePossessedDevice) {
                case 'Withings Activité Pop':
                $withings = $this->get('app.withings');
                $withings->authenticate($possessedDevice);
                $json = $withings->getActivities($withings->getUserID() , $data['date_deb'], $data['date_fin']);
                $data['nbPas'] = $json['global']['steps'];
                $data['nbKm'] = round($json['global']['distance']/1000, 2);
                break;
                case 'Jawbone UP 24':
                $jawbone = $this->get('app.jawbone');
                $json = $jawbone->getMoves($possessedDevice->getAccessTokenJawbone(), $data['date_deb'], $data['date_fin']);
                $data['nbPas'] = $json['global']['steps'];
                $data['nbKm'] = round($json['global']['distance']/1000, 2);
                break;
                case 'Googlefit':
                $montre_service = $this->get('app.googlefit');
                break;
                default:
                $data['json'] = null;
                break;
            }

            if($data['nbPas'] == null || $data['nbKm'] == null){
                $data['error'] = true;
            }

            return $this->render('AppBundle:Ajax:ajax_add_defis.html.twig', array(
                "data" => $data
                ));
        }
    }

    /**
     * Génération des niveaux
     * 
     * @Route("/levels", name="levels")
     */
    public function generateLevels(){
        $em = $this->get('doctrine')->getManager();

        $level1 = new Level();
        $level1->setNumLevel(1);
        $level1->setNbPoints(10);

        $em->persist($level1);

        $prevPoints = $level1->getNbPoints();
        $prevLevel = $level1;

        for($i=2; $i<=50; $i++){
            $gain = ($prevPoints * 1.2);

            $level = new Level();
            $level->setNumLevel($i);
            $level->setNbPoints(intval($prevLevel->getNbPoints() + $gain));

            $em->persist($level);

            $prevPoints = $gain;
            $prevLevel = $level;
        }

        $em->flush();

        return $this->redirectToRoute('homepage');
    }
}

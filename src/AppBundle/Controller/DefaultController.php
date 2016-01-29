<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DeviceType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\NewPossessedDeviceType;
use AppBundle\Entity\PossessedDevice;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(){
        return $this->render('AppBundle:Default:index.html.twig', array(
            ));
    }
      /**
     * @Route("/apropos", name="apropos")
     */
    public function aproposAction(){
        return $this->render('AppBundle:Default:apropos.html.twig');
    }

    /**
     * @Route("/withings/{possessedDevice}", name="withings")
     */
    public function withingsAction($possessedDevice){
        $withings = $this->get("app.withings");
        $withings->connection();

        $em = $this->get('doctrine')->getManager();

        $current_user = $this->container->get('security.context')->getToken()->getUser();
        $possessedDevice->setUser($current_user);

        $possessedDevice->setAccessTokenKeyWithings($withings->getAccessTokenKey());
        $possessedDevice->setAccessTokenSecretWithings($withings->getAccessTokenSecret());
        $possessedDevice->setUserIdWithings($withings->getUserID());

        $withingsDevice = $em->getRepository('AppBundle\Entity\PossessedDevice')->find(1); // Withings DeviceType Object
        $possessedDevice->setDeviceType($withingsDevice);


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
                $possessedDevice->setDeviceType($form);
                $withings = $this->get("app.withings");
                $withings->connection();
                $this->redirectToRoute('withings', array($possessedDevice));
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

    /**
     * @Route("/jawbone/{id}/moves", name="jawbone_moves")
     */
    public function jawboneMovesAction($id){
        $possessedDevice = $this->getDoctrine()->getRepository('AppBundle:PossessedDevice')->find($id);;

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
     * @Route("/defis", name="defis")
     */
    public function defisAction(){
        return $this->render('AppBundle:Default:defis.html.twig');
    }
}


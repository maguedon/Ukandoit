<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(){
        $current_user = $current_user = $this->container->get('security.context')->getToken()->getUser();
        return $this->render('AppBundle:Default:index.html.twig', array(
            'current_user' => $current_user
            ));
    }


    /**
     * @Route("/withings", name="withings")
     */
    public function withingsAction(){
        $withings = $this->get("app.withings");
        var_dump($withings);
        $withings->getRequestToken();
    }

    /**
     * @Route("/withings/token", name="token")
     */
    public function getWithingsTokenAction(){
        $withings = $this->get("app.withings");
        $json = $withings->getAccessToken();
        var_dump($json);
        return $this->render('AppBundle:Default:withings.html.twig');
    }

    /**
     * @Route("/jawbone/moves", name="moves")
     */
    public function jawboneMovesAction(){
        $current_user = $current_user = $this->container->get('security.context')->getToken()->getUser();
        $possessedDevice = $current_user->getLastPossessedDevice();

        $jawbone = $this->get("app.jawbone");
        $json = $jawbone->getMoves($possessedDevice->getAccessTokenJawbone());

        $hourly_totals = $json['items'][0]['details']['hourly_totals'];

        return $this->render('AppBundle:Default:jawboneMoves.html.twig', array(
            'hourly_totals' => $hourly_totals
            ));
    }
}


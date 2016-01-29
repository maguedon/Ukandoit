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
        return $this->render('AppBundle:Default:index.html.twig');
    }
      /**
     * @Route("/apropos", name="apropos")
     */
    public function aproposAction(){
        return $this->render('AppBundle:Default:apropos.html.twig');
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

    // A deplacer dans le bundle user ?

   /**
     * @Route("/defis", name="defis")
     */
    public function defisAction(){
        return $this->render('AppBundle:Default:defis.html.twig');
    }
}

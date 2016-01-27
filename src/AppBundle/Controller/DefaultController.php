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
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ]);
    }

    /**
     * @Route("/jawbone", name="jawbone")
     */
    public function jawboneAction(){
        $jawbone = $this->get("app.jawbone");
        $url = $jawbone->connexion();
        return $this->redirect($url);
    }

    /**
     * @Route("/jawbone/token", name="token")
     */
    public function getJawboneTokenAction(){
        $jawbone = $this->get("app.jawbone");
        $json = $jawbone->getToken();
        var_dump($json);
        return $this->render('AppBundle:Default:jawbone.html.twig');
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
}

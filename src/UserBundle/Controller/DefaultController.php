<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
     * @Route("/user", name="user")
     */
class DefaultController extends Controller
{

	/**
     * @Route("/object/new", name="new_object")
     */
	public function addObject(){
		
	}
}

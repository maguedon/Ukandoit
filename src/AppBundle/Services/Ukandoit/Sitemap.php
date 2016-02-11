<?php

namespace AppBundle\Services\Ukandoit;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\RouterInterface;

class Sitemap
{
    private $router;
    private $em;

    public function __construct(RouterInterface $router, ObjectManager $em)
    {
        $this->router = $router;
        $this->em = $em;
    }
	
    /**
     * Génère l'ensemble des valeurs des balises <url> du sitemap.
     *
     * @return array Tableau contenant l'ensemble des balise url du sitemap.
     */
    public function generer()
    {
        $urls = array();        

        $challenges = $this->em->getRepository('AppBundle:Challenge')->findAll();

        foreach ($challenges as $challenge) {
            $urls[] = array(
                'loc' => $this->router->generate('showChallenge', array('challenge' => $challenge->getId()), true)
            );
        }

        return $urls;
    }
} 
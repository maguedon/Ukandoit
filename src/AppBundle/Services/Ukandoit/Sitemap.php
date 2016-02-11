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

        $urls[] = array(
            'loc' => $this->router->generate('homepage', array(), true)
            );

        $urls[] = array(
            'loc' => $this->router->generate('add_defis', array(), true)
            );

        $urls[] = array(
            'loc' => $this->router->generate('about', array(), true)
            );

        $urls[] = array(
            'loc' => $this->router->generate('contact', array(), true)
            );

        $urls[] = array(
            'loc' => $this->router->generate('legals', array(), true)
            );

        $urls[] = array(
            'loc' => $this->router->generate('challenges', array(), true)
            );

        $urls[] = array(
            'loc' => $this->router->generate('fos_user_registration_register', array(), true)
            );

        $urls[] = array(
            'loc' => $this->router->generate('fos_user_security_login', array(), true)
            );

        $challenges = $this->em->getRepository('AppBundle:Challenge')->findAll();

        foreach ($challenges as $challenge) {
            $urls[] = array(
                'loc' => $this->router->generate('showChallenge', array('challenge' => $challenge->getId()), true)
                );
        }

        $users = $this->em->getRepository('AppBundle:User')->findAll();

        foreach ($users as $user) {
            $urls[] = array(
                'loc' => $this->router->generate('user_other', array('id' => $user->getId()), true)
                );
        }
        return $urls;
    }
} 
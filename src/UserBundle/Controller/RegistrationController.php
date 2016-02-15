<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace UserBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Stats;

class RegistrationController extends BaseController
{
    public function registerAction()
    {
        // Si on est déjà connecté on redirige vers le profil
        $current_user = $this->container->get('security.context')->getToken()->getUser();
        if($current_user != "anon."){
            $this->setFlash('message', 'Vous êtes déjà connecté !');
            return new RedirectResponse($this->container->get('router')->generate("fos_user_profile_show"));
        }

        $form = $this->container->get('fos_user.registration.form');
        $formHandler = $this->container->get('fos_user.registration.form.handler');
        $confirmationEnabled = $this->container->getParameter('fos_user.registration.confirmation.enabled');

        $process = $formHandler->process($confirmationEnabled);
        if ($process) {
            $user = $form->getData();

            $levels = $this->container->get('doctrine')->getManager()->getRepository('AppBundle:Level')->findAll();

            $authUser = false;
            if ($confirmationEnabled) {
                $this->container->get('session')->set('fos_user_send_confirmation_email/email', $user->getEmail());
                $route = 'fos_user_registration_check_email';
                $this->setFlash('message', 'Un mail de confirmation vous a été envoyé !');
            } else {
                $authUser = true;
                $route = 'homepage';
                $this->setFlash('message', 'Votre compte a été créé. Bienvenue !');
            }

            $url = $this->container->get('router')->generate($route);
            $response = new RedirectResponse($url);

            if ($authUser) {
                $this->authenticateUser($user, $response);
            }

            $em = $this->container->get('doctrine')->getManager();
            $stats = new Stats();
            
            $em->persist($stats);

            $user->setStats($stats);

            $em->flush();

            return $response;
        }

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:register.html.'.$this->getEngine(), array(
            'form' => $form->createView(),
            ));
    }

        /**
     * Tell the user to check his email provider
     */
        public function checkEmailAction()
        {
            $email = $this->container->get('session')->get('fos_user_send_confirmation_email/email');
            $this->container->get('session')->remove('fos_user_send_confirmation_email/email');
            $user = $this->container->get('fos_user.user_manager')->findUserByEmail($email);

            if (null === $user) {
                throw new NotFoundHttpException(sprintf('The user with email "%s" does not exist', $email));
            }

            return new RedirectResponse($this->container->get('router')->generate("homepage"));
        }

    /**
     * @param string $action
     * @param string $value
     */
    protected function setFlash($action, $value)
    {
        $this->container->get('session')->getFlashBag()->set($action, $value);
    }

    protected function getEngine()
    {
        return $this->container->getParameter('fos_user.template.engine');
    }
}

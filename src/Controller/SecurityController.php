<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
            return $this->render('security/login.html.twig', [
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \Exception('logout() should never be reached');
        //throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    public function accessDenied(): Response
    {
        // Redirige a la página de acceso prohibido
        return $this->render('accessdenied/access_denied.html.twig', [], new Response('', Response::HTTP_FORBIDDEN));
    }

}

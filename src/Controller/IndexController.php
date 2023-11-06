<?php
namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class IndexController extends AbstractController
{
    #[Route('/', name: 'login', methods: ['GET'])]
    public function loginForm(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('index.html.twig', [
            'last_username' => '',
            'error' => ''
        ]);
    }

    #[Route('/', name: 'login_check', methods: ['POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        if ($error) {
            return $this->render('index.html.twig', [
                'last_username' => $lastUsername,
                'error' => $error
            ]);
        }

        return $this->render('home.html.twig');
    }
}
<?php
// src/Security/AuthenticationEntryPoint.php
// namespace App\Security;

// use Symfony\Component\HttpFoundation\RedirectResponse;
// use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
// use Symfony\Component\Security\Core\Exception\AuthenticationException;
// use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Annotation\Route;

// class AuthenticationEntryPoint implements AuthenticationEntryPointInterface
// {
//     private $urlGenerator;

//     public function __construct(UrlGeneratorInterface $urlGenerator)
//     {
//         $this->urlGenerator = $urlGenerator;
//     }

//     public function start(Request $request, AuthenticationException $authException = null): RedirectResponse
//     {
//         // Add a custom flash message and redirect to the login page
//         $this->addFlash('note', 'You have to log in to access this page.');

//         return new RedirectResponse($this->urlGenerator->generate('security_login'));
//     }
// }

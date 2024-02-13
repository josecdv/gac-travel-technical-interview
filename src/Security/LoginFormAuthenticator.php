<?php

namespace App\Security;

use App\Entity\Users;
use App\Repository\UserRepository;
use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class LoginFormAuthenticator extends AbstractAuthenticator
{
    private UsersRepository $userRepository;
    private UrlGeneratorInterface $router;

    public function __construct(UsersRepository $userRepository, UrlGeneratorInterface $router)
    {
        $this->userRepository = $userRepository;
        $this->router = $router; // initialize $router here
    }

    public function supports(Request $request): ?bool
    {
        //dd('supports');
		return (in_array($request->getPathInfo(), ['/', '/login']) && $request->isMethod('POST'));				
        //return ($request->getPathInfo() === '/login' && $request->isMethod('POST'));
    }

    public function authenticate(Request $request): PassportInterface
    {
        //dd('authenticate');
							 
        $username = $request->request->get('username');
        $password = $request->request->get('password');

        return new Passport(
            new UserBadge($username, function (string $userIdentifier) {
                $user = $this->userRepository->findOneBy(['username' => $userIdentifier]);
                if (!$user) {
                    throw new UsernameNotFoundException();
                }
                return $user;
            }),
            new CustomCredentials(function (string $credentials, Users $user) {
                return $credentials === $user->getPassword();
            }, $password)
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse($this->router->generate('products_index'));
        //dd('success');
        // TODO: Implement onAuthenticationSuccess() method.
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
         $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
         return new RedirectResponse(
             $this->router->generate('app_login')
         );
        //return new RedirectResponse($this->router->generate('app_login'));
        //dd('failure');
        // TODO: Implement onAuthenticationFailure() method.
    }																					  

}
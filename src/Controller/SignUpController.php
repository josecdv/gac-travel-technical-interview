<?php
namespace App\Controller;

use App\Entity\SignUpForm;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Type\SignUpFormType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\Users;
use Symfony\Component\Form\FormFactoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class SignUpController extends AbstractController
{
    #[Route('/sign-up', methods: ['GET'], name: 'signup')]
    public function index(FormFactoryInterface $formFactory): Response
    {
        $signUpForm = new SignUpForm();
        $form = $formFactory->createNamed('', SignUpFormType::class, $signUpForm);
        return $this->render('sign-up.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/sign-up', methods: ['POST'])]
    public function signUp(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $username = $request->request->get('username');
        $password = $request->request->get('password');

        $user = new Users();
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $password
        );

        $user->setUsername($username);
        $user->setPassword($hashedPassword);
        $user->setActive(true);
        $user->setCreatedAt(new \DateTime());

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->render('success.html.twig');
    }
}
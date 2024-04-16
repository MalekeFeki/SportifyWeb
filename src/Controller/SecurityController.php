<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Mime\Email;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils,UserRepository $repo,Request $request): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $userip=$request->getClientIp();
        $countRecentLoginFail=0;
        if($lastUsername){
            
        }

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
    

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): Response
    {
        return $this->render('user/base.html.twig');    }
    #[Route(path: '/forgotpass', name: 'forgot_pass')]
    public function reinitPass(): Response
    {
        return $this->render('security/forgot_password.html.twig');
    }
    private function sendWelcomeEmail(User $user, MailerInterface $mailer): void
    {
        $email = (new Email())
            ->from('malekfeki18@gmail.com') // Set your email address here
            ->to($user->getEmail()) // Send email to the user
            ->subject('Welcome to Our Website')
            ->html('<p>Hello ' . $user->getNom() . ',<br>Welcome to our website!</p>');
    
        $mailer->send($email);
    }
    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
// Hasher le mot de passe
$hashedPassword = password_hash($user->getPassword(),PASSWORD_DEFAULT);
$user->setMdp($hashedPassword);
            $entityManager->persist($user);
            $entityManager->flush();

             // Send welcome email
        $this->sendWelcomeEmail($user, $mailer);
        // Émettre une notification Mercure pour informer des nouveaux utilisateurs
        //$update = new Update(
        //    '/notifications/user-added',
        //    json_encode(['message' => 'New user added'])
        //);
        //$this->publisher->__invoke($update);
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
   
}
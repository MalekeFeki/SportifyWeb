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
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Security\Core\Exception\LockedException;

class SecurityController extends AbstractController
{  private $cache;

    public function __construct(AdapterInterface $cache)
    {
        $this->cache = $cache;
    }
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, UserRepository $repo, Request $request): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $userip = $request->getClientIp();
    
        // Fetch login attempts from cache
        $loginAttempts = $this->cache->getItem('login_attempts_' . $userip)->get();
    
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'login_attempts' => $loginAttempts, // Pass login attempts to the template
        ]);
    }
    
    

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): Response
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
        return $this->render('base.html.twig');
    }
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
            ->html('<p>Bonjour ' . $user->getNom() . ',<br>Bienvenue dans notre site web Sportify!</p>');
    
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
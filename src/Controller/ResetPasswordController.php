<?php

namespace App\Controller;

use App\Util\RandomPasswordGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends AbstractController
{
    private $mailer;
    private $passwordGenerator;

    public function __construct(MailerInterface $mailer, RandomPasswordGenerator $passwordGenerator)
    {
        $this->mailer = $mailer;
        $this->passwordGenerator = $passwordGenerator;
    }

   
    #[Route("/forgot-password", name:"forgot_password")]
    
    public function forgotPassword(Request $request): void
    {
        // Récupérer l'e-mail de l'utilisateur à partir du formulaire
        $email = $request->request->get('email');

        // Générer un nouveau mot de passe aléatoire
        $newPassword = $this->passwordGenerator->generate();

       
    }
    #[Route("/pass_confirm", name:"password_reset_confirmation")]
    public function index(): Response
    {
        return $this->render('reset_password/index.html.twig');
    }

}
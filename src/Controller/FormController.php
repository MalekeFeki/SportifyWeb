<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


class FormController extends AbstractController
{
    
    #[Route('/submit-form', name:'submit_form', methods:["POST"])]
    public function submitForm(Request $request, MailerInterface $mailer): Response
    {
       // Retrieve form data
       $name = $request->request->get('name');
       $email = $request->request->get('email');
       $gym = $request->request->get('gym');
       $comment = $request->request->get('comment');

       // Send email
       $email = (new Email())
           ->from($email)
           ->to('rayosbot@gmail.com') // Replace with your email address
           ->subject('New submission from ' . $name)
           ->html("<p>Name: $name</p><p>Email: $email</p><p>Gym: $gym</p><p>Comment: $comment</p>");

       $mailer->send($email);

       // Process form submission and send email

       return new Response('Form submitted successfully!');
    }

}

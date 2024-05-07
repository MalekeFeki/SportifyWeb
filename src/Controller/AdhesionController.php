<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Salle;
use App\Entity\Adhesion;
use App\Repository\AdhesionRepository;
use App\Form\AdhesionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[Route('/adhesion')]
class AdhesionController extends AbstractController
{
    #[Route('/', name: 'app_adhesion_index', methods: ['GET'])]
    public function index(AdhesionRepository $adhesionRepository): Response
    {
        return $this->render('adhesion/ViewAdhesions.html.twig', [
            'adhesions' => $adhesionRepository->findAll(),
        ]);
    }
    #[Route('/submissions/{idS}', name: 'app_adhesion_submissions', methods: ['GET'])]
    public function submissions(Salle $salle): Response
{

       return $this->render('adhesion/UserAdhesionSubmission.html.twig',[
            'salle'=> $salle,

       ]);
}


    #[Route('/new/{idS}', name: 'app_adhesion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer,int $idS): Response
    {
        // Retrieve the current user
        $user = $entityManager->getRepository(User::class)->find(1);
        $salle = $entityManager->getRepository(Salle::class)->find($idS);
        
        $plan = $request->query->get('plan');
        $price = $request->query->get('price');
        $duration = $request->query->get('duration');
       
        $adhesion = new Adhesion();
        $adhesion->setId($user);
        $adhesion->setGymId($salle);
        $adhesion->setUsername($user->getNom()); // Pre-fill username
        $adhesion->setGymname($salle->getNoms()); // Pre-fill gym name
        $adhesion->setTypea($plan); // Pre-fill membership type
        $adhesion->setPrice($price); // Pre-fill price
        // Pre-fill start date (current date)
        $startDate = new \DateTime();
        $adhesion->setDebuta($startDate);
        // Pre-fill end date (current date + duration)
        $endDate = clone $startDate;
        $interval = 'P' . intval($duration) . 'M'; // Duration in months
        $endDate->add(new \DateInterval($interval));
        $adhesion->setFina($endDate);
    

        // Create the form
        $form = $this->createForm(AdhesionType::class, $adhesion);
        $form->handleRequest($request);

        // Handle form submission
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$entityManager->contains($adhesion)) {
                $entityManager->persist($adhesion);
            }
            $entityManager->flush();

            $email = (new Email())
            ->from('rayosbot@gmail.com') // Set the sender email address
            ->to($user->getEmail()) // Set the recipient email address
            ->subject('Your adhesion has been submitted successfully')
            ->html($this->renderView('adhesion/adhesion_confirmation.html.twig', ['adhesion' => $adhesion])); // Render the HTML content of the email

            $mailer->send($email); // Send the email

            return $this->redirectToRoute('app_paiement_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('adhesion/new.html.twig', [
            'adhesion' => $adhesion,
            'form' => $form,
        ]);
    }

    #[Route('/{ida}', name: 'app_adhesion_show', methods: ['GET'])]
    public function show(Adhesion $adhesion): Response
    {
        return $this->render('adhesion/show.html.twig', [
            'adhesion' => $adhesion,
        ]);
    }

    #[Route('/{ida}/edit', name: 'app_adhesion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Adhesion $adhesion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdhesionType::class, $adhesion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_adhesion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('adhesion/edit.html.twig', [
            'adhesion' => $adhesion,
            'form' => $form,
        ]);
    }

    #[Route('/{ida}', name: 'app_adhesion_delete', methods: ['POST'])]
    public function delete(Request $request, Adhesion $adhesion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adhesion->getIda(), $request->request->get('_token'))) {
            $entityManager->remove($adhesion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_adhesion_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/bmi-calculator', name: 'bmi_calculator')]
    public function bmiCalculator(): Response
    {
    return $this->render('Bmi-Calculator.html.twig');
    }
    #[Route('/Contact', name:'Contact')]
    public function Contact(): Response
    {
        return $this->render('Contact.html.twig');
    }
}

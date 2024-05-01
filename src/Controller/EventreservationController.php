<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Eventreservation;
use App\Form\EventreservationType;
use App\Repository\EventreservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;



#[Route('/reservation')]
class EventreservationController extends AbstractController
{
    #[Route('/show', name: 'app_eventreservation_index', methods: ['GET'])]
    public function index(EventreservationRepository $eventreservationRepository): Response
    {
        return $this->render('eventreservation/index.html.twig', [
            'eventreservations' => $eventreservationRepository->findAll(),
        ]);
    }
    #[Route('/detect-cin/{idevent}', name: 'app_eventreservation_detect_cin', methods: ['POST'])]
public function detectCIN(Request $request, Evenement $event): Response
{
    // Receive the captured image data
    $imageDataUrl = $request->request->get('capturedImageData');

    // Convert base64 image data to a file
    $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageDataUrl));

    // Create a temporary file path with a .jpg extension
    $tempFilePath = tempnam(sys_get_temp_dir(), 'image_') . '.png';
    
    // Save the image data to the temporary file
    file_put_contents($tempFilePath, $imageData);
    // echo($tempFilePath);
    // Execute the Python script
    $process = new Process(['C:/Users/RAY3N/anaconda3/python.exe', 'C:\Users\RAY3N\Desktop\pi symfony\web_sportify\python\file1.py', $tempFilePath]);
    $process->run();
    
    // Check if the process was successful
    if (!$process->isSuccessful()) {
        $error = $process->getErrorOutput();
        return $this->json(['error' => 'Python script failed: ' . $error]);
    }
    echo($process->getOutput());
    // Get the output of the Python script (ID numbers)
    $idNumbers = json_decode($process->getOutput(), true);
    // Process the ID numbers as needed
    // ...

    // Return a response
    return $this->json(['cin' => $idNumbers['id_numbers'][0] ?? "1"]);
}

    #[Route('/new/{idevent}', name: 'app_eventreservation_new', methods: ['GET', 'POST'])]
    public function new(MailerInterface $mailer, Request $request, EntityManagerInterface $entityManager, Evenement $event, UrlGeneratorInterface $urlGenerator): Response
    {
        $eventreservation = new Eventreservation();
        $eventreservation->setEventid($event); // Set the event for the reservation

        
        $form = $this->createForm(EventreservationType::class, $eventreservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $recaptchaResponse = $request->request->get('g-recaptcha-response');
            $entityManager->persist($eventreservation);
            $entityManager->flush();
            $email = (new Email())
                ->from('rayosbot@gmail.com')
                ->to($eventreservation->getEmail())
                ->subject('Reservation Confirmation')
                ->html($this->renderView('reservation_confirmation.html.twig', [
                    'username' => $eventreservation->getNom(),
                    'event_name' => $eventreservation->getPrenom(),
                ]));

            $mailer->send($email);
            return new RedirectResponse(
                $urlGenerator->generate('app_evenement_show', ['idevent' => $event->getIdevent()])
            );
        }

        return $this->renderForm('eventreservation/new.html.twig', [
            'eventreservation' => $eventreservation,
            'form' => $form,
            'evenement' => $event,
        ]);
    }

    #[Route('/{reservationid}', name: 'app_eventreservation_show1', methods: ['GET'])]
    public function show(Eventreservation $eventreservation): Response
    {
        return $this->render('eventreservation/show.html.twig', [
            'eventreservation' => $eventreservation,

        ]);
    }

    #[Route('/{reservationid}/edit', name: 'app_eventreservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Eventreservation $eventreservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EventreservationType::class, $eventreservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_eventreservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('eventreservation/edit.html.twig', [
            'eventreservation' => $eventreservation,
            'form' => $form,
        ]);
    }

    #[Route('/{reservationid}', name: 'app_eventreservation_delete', methods: ['POST'])]
    public function delete(Request $request, UrlGeneratorInterface $urlGenerator, Eventreservation $eventreservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $eventreservation->getReservationid(), $request->request->get('_token'))) {
            $entityManager->remove($eventreservation);
            $entityManager->flush();
        }
        $event = $eventreservation->getEventid();
        $idevent = $event->getIdevent();
        return new RedirectResponse(
            $urlGenerator->generate('app_event_show', ['idevent' => $idevent])
        );
    }
}

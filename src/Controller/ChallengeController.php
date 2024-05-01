<?php

namespace App\Controller;

use App\Entity\Challenge;
use App\Form\ChallengeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\QrCodeGeneratorService;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;


#[Route('/challenge')]
class ChallengeController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private QrCodeGeneratorService $qrCodeGenerator;

    public function __construct(EntityManagerInterface $entityManager, QrCodeGeneratorService $qrCodeGenerator)
    {
        $this->entityManager = $entityManager;
        $this->qrCodeGenerator = $qrCodeGenerator;
    }

    #[Route('/', name: 'app_challenge_index', methods: ['GET'])]
    public function index(): Response
    {
        $challenges = $this->entityManager
            ->getRepository(Challenge::class)
            ->findAll();

        return $this->render('challenge/index.html.twig', [
            'challenges' => $challenges,
        ]);
    }

    #[Route('/new', name: 'app_challenge_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $challenge = new Challenge();
        $form = $this->createForm(ChallengeType::class, $challenge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Check if the description is empty
            $description = $challenge->getDescription();
            if (empty($description)) {
                return new Response('<script>alert("Description cannot be empty"); window.history.back();</script>');
            }

            // Check if the description contains the word "chat"
            if (stripos($description, 'chat') !== false) {
                return new Response('<script>alert("This description contains bad words"); window.history.back();</script>');
            }

            $this->entityManager->persist($challenge);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_challenge_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('challenge/new.html.twig', [
            'challenge' => $challenge,
            'form' => $form,
        ]);
    }

    #[Route('/{idc}', name: 'app_challenge_show', methods: ['GET'])]
    public function show(Challenge $challenge): Response
    {
        return $this->render('challenge/show.html.twig', [
            'challenge' => $challenge,
        ]);
    }

    #[Route('/{idc}/edit', name: 'app_challenge_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Challenge $challenge): Response
    {
        $form = $this->createForm(ChallengeType::class, $challenge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_challenge_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('challenge/edit.html.twig', [
            'challenge' => $challenge,
            'form' => $form,
        ]);
    }

    #[Route('/{idc}', name: 'app_challenge_delete', methods: ['POST'])]
    public function delete(Request $request, Challenge $challenge): Response
    {
        if ($this->isCsrfTokenValid('delete'.$challenge->getIdc(), $request->request->get('_token'))) {
            $this->entityManager->remove($challenge);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_challenge_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{idc}/qr-code', name: 'app_challenge_generate_qr_code', methods: ['GET'])]
    public function generateQrCode(Challenge $challenge): Response
    {
        $writer = new PngWriter();

        // Build QR code content for the challenge
        $content = "Challenge ID: {$challenge->getIdc()}\n";
        $content .= "Nom: {$challenge->getNom()}\n";
        $content .= "Difficulty: {$challenge->getDifficulty()}\n";
        $content .= "Description: {$challenge->getDescription()}\n";

        $qrCode = QrCode::create($content)
            ->setSize(300)
            ->setMargin(10);

        $qrCodeImage = $writer->write($qrCode)->getDataUri();

        // Render the QR code in the view
        return $this->render('challenge/qr_code.html.twig', [
            'qrCodeImage' => $qrCodeImage,
            'challenge' => $challenge,
        ]);
    
}}

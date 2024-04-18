<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use BaconQrCode\Encoder\QrCode;
use BaconQrCode\Common\ErrorCorrectionLevel;
use App\Entity\Challenge; // Import the Challenge entity if needed
use Doctrine\ORM\EntityManagerInterface;

class QrCodeController extends AbstractController
{
    /**
     * @Route("/generate-qr-code", name="app_generate_qr_code", methods={"GET"})
     */
    public function generateQrCode(EntityManagerInterface $entityManager): Response
    {
        $challenges = $entityManager->getRepository(Challenge::class)->findAll();

        $qrText = '';
        foreach ($challenges as $challenge) {
            $qrText .= $challenge->getIdc() . ' | ' . $challenge->getNom() . ' | ' . $challenge->getDifficulty() . ' | ' . $challenge->getDescription() . "\n";
        }

        $qrCode = new QrCode($qrText);
        $qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevel(ErrorCorrectionLevel::LOW));

        $response = new Response($qrCode->get(), Response::HTTP_OK, ['Content-Type' => 'image/png']);

        return $response;
    }
}

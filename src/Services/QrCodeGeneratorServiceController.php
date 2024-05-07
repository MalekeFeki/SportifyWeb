<?php

namespace App\Service;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use App\Entity\Challenge;


class QrCodeGeneratorService
{
    public function generateQrCode(Challenge $challenge): string
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

        return $writer->write($qrCode)->getDataUri();
    }
}

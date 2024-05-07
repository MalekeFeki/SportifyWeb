<?php

namespace App\Service;

use App\Entity\Evenement as EntityEvenement;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\String\UnicodeString;

class PdfService
{
    public static function printEvent(EntityEvenement $event, $name)
    {
        $htmlFilePath = 'C:\Users\RAY3N\Desktop\pi symfony\web_sportify\SportifyWeb\templates\index.html.twig';
        $html = file_get_contents($htmlFilePath);

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml(self::generateHtml($event,$html));
        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();
        $pdfFilePath = 'C:\Users\RAY3N\Desktop\pi symfony\web_sportify\SportifyWeb\public\pdf_directory' . $name . '.pdf';
        file_put_contents($pdfFilePath, $dompdf->output());

        return $pdfFilePath; // Return the file path of the saved PDF

        
    }

    private static function generateHtml(EntityEvenement $event,String $html)
    {
        $photoPath = $event->getPhoto();
$photoData = file_get_contents($photoPath);
$photoBase64 = base64_encode($photoData);

// Replace '{{event_photo}}' in the HTML with base64-encoded image data
$html = str_replace('{{event_photo}}', 'data:image/jpeg;base64,' . $photoBase64, $html);
        $html = (new UnicodeString($html))
            ->replace('{{event_name}}', $event->getNomev())
            ->replace('{{event_description}}', $event->getDescrptionev())
            ->replace('{{event_date}}', $event->getDateddebutev()->format('Y-m-d'))
            
            // Add more replacements as needed
            ->toString();

        return $html;
    }
}


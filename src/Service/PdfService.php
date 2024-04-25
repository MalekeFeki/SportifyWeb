<?php

namespace App\Service;

use App\Entity\Evenement;
use Knp\Snappy\Pdf;

class PdfService
{
    private $snappy;

    public function __construct(Pdf $snappy)
    {
        $this->snappy = $snappy;
    }

    public function generateEventPdf($event, $name)
    {
        $htmlFilePath = 'C:\Users\RAY3N\Desktop\pi symfony\web_sportify\SportifyWeb\src\Service\file_pdf.html.twig';
        $html = file_get_contents($htmlFilePath);

        // Replace placeholders with event data
        $html = $this->replacePlaceholders($html, $event);

        // Generate PDF from HTML content
        $pdf = $this->snappy->getOutputFromHtml($html);

        // Save PDF to file or return as response
        // Example: Save PDF to a file named event.pdf in the public directory

        file_put_contents($name . '.pdf', $pdf);
    }

    private function replacePlaceholders(string $html, Evenement $event): string
    {
        
        $htmlReplacements = [
            '{{event_name}}' => $event->getNomev(),
            '{{event_description}}' => $event->getDescrptionev(),
            '{{event_date}}' => $event->getDateddebutev()->format('Y-m-d'),
            '{{event_end_date}}' => $event->getDatedfinev()->format('Y-m-d'),
            '{{event_time}}' => $event->getHeureev(),
            '{{event_photo}}' => $event->getPhoto(),
            '{{event_location}}' => $event->getLieu(),
            '{{event_city}}' => $event->getCity(),
            '{{event_telephone}}' => $event->getTele(),
            '{{event_email}}' => $event->getEmail(),
            '{{event_fb_link}}' => $event->getFbLink(),
            '{{event_ig_link}}' => $event->getIgLink(),
            '{{event_genre}}' => $event->getGenreevenement(),
            '{{event_type}}' => $event->getTypeev(),
            '{{event_interested}}' => $event->getNombrepersonneinteresse(),
            '{{event_capacity}}' => $event->getCapacite(),
            '{{event_lat}}' => $event->getLat(),
            '{{event_lon}}' => $event->getLon(),
            '{{event_role}}' => $event->getRole(),
        ];
        
        $html = strtr($html, $htmlReplacements);
        
        return $html;
        
    }
}

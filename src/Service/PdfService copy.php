<?php

namespace App\Service;

use App\Entity\Evenement;
use Knp\Snappy\Pdf;
use Symfony\Component\String\UnicodeString;
class PdfService
{
    private $snappy;

    public function __construct(Pdf $snappy)
    {
        $this->snappy = $snappy;
    }

    public function generateEventPdf($event,$name)
    {
       // Read HTML content from file
       $htmlFilePath = '/path/to/your/html/file.html';
       $html = file_get_contents($htmlFilePath);

       // Replace placeholders with event data
       $html = $this->replacePlaceholders($html, $event);

       // Generate PDF from HTML content
       $pdf = $this->snappy->getOutputFromHtml($html);

       // Save PDF to file or return as response
       // Example: Save PDF to a file named event.pdf in the public directory
       
        file_put_contents($name.'.pdf', $pdf);
    }

   private function replacePlaceholders(string $html, Evenement $event): string
    {
        // Replace placeholders with event data
        $html = (new UnicodeString($html))
            ->replace('{{event_name}}', $event->getNomev())
            ->replace('{{event_description}}', $event->getDescrptionev())
            ->replace('{{event_date}}', $event->getDateddebutev()->format('Y-m-d'))
            // Add more replacements as needed
            ->toString();

        return $html;
    }
}

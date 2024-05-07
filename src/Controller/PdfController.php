<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Dompdf\Dompdf;
use Dompdf\Options;

class PdfController extends AbstractController
{
    public function generatePdf()
    {
        // Create an instance of Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);

        // Render the HTML as PDF
        $html = $this->renderView('pdf/template.html.twig', [
            // Pass any data required for rendering your PDF template
        ]);
        $dompdf->loadHtml($html);

        // (Optional) Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the PDF
        $dompdf->render();

        // Stream the PDF to the browser
        $dompdf->stream('document.pdf', [
            'Attachment' => false
        ]);
    }
}

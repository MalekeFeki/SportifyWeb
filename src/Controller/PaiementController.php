<?php

namespace App\Controller;

use App\Entity\Paiement;
use App\Entity\User ;
use App\Entity\Adhesion ;
use APP\Entity\Salle ;
use App\Repository\PaiementRepository;
use App\Form\PaiementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


#[Route('/paiement')]
class PaiementController extends AbstractController
{
    #[Route('/', name: 'app_paiement_index', methods: ['GET'])]
    public function index(PaiementRepository $paiementRepository): Response
    {
        return $this->render('paiement/index.html.twig', [
            'paiements' => $paiementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_paiement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {

        $user = $entityManager->getRepository(User::Class)->find(1);
        $adhesion = $entityManager->getRepository(Adhesion::class)->findOneBy(['id' => $user->getId()]);
        $salle = $entityManager->getRepository(Salle::Class)->find(1);
        

        $paiement = new Paiement();
        $paiement->setId($user);
        $paiement->setAdhesion($adhesion); // Set the adhesion association
        $paiement->setUsername($user->getNom()); // Pre-fill username
        $paiement->setEmail($user->getEmail());
        $paiement->setPrice($adhesion->getPrice()); // Set the price from adhesion
        $paiement->setPdate(new \DateTime());
        $form = $this->createForm(PaiementType::class, $paiement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($paiement);
            $entityManager->flush();

            $pdfContent = $this->renderView('paiement/PdfTemplate.html.twig', [
                'user'=>$user,
                'paiement' => $paiement,
                'ahdesion' =>$adhesion ,
                'salle' =>$salle
            ]);

            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($pdfContent);

            // (Optional) Set paper size and orientation
            $dompdf->setPaper('A4', 'portrait');

            // Render the PDF
            $dompdf->render();

            // Save the PDF to a file or stream it to the browser
            $pdfOutput = $dompdf->output();
            // Example: stream the PDF to the browser
            $response = new Response($pdfOutput);
            $response->headers->set('Content-Type', 'application/pdf');
            $response->headers->set('Content-Disposition', 'inline; filename="paiement_receipt.pdf"');

            return $response;

            return $this->redirectToRoute('app_paiement_index', [], Response::HTTP_SEE_OTHER);

        }
        
        return $this->renderForm('paiement/new.html.twig', [
            'paiement' => $paiement,
            'form' => $form,
        ]);
    }

    #[Route('/{idp}', name: 'app_paiement_show', methods: ['GET'])]
    public function show(Paiement $paiement): Response
    {
        return $this->render('paiement/show.html.twig', [
            'paiement' => $paiement,
        ]);
    }

    #[Route('/{idp}/edit', name: 'app_paiement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Paiement $paiement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PaiementType::class, $paiement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_paiement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('paiement/edit.html.twig', [
            'paiement' => $paiement,
            'form' => $form,
        ]);
    }

    #[Route('/{idp}', name: 'app_paiement_delete', methods: ['POST'])]
    public function delete(Request $request, Paiement $paiement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$paiement->getIdp(), $request->request->get('_token'))) {
            $entityManager->remove($paiement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_paiement_index', [], Response::HTTP_SEE_OTHER);
    }
}

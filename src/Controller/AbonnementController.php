<?php

namespace App\Controller;

use App\Entity\Abonnement;
use App\Entity\Salle;
use App\Form\AbonnementType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AbonnementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/abonnement')]
class AbonnementController extends AbstractController
{
    #[Route('/', name: 'abonnement_index', methods: ['GET'])]
    public function index(AbonnementRepository $abonnementRepository): Response
    {
        $abonnements = $abonnementRepository->findAll();

        return $this->render('abonnement/index.html.twig', [
            'abonnements' => $abonnements,
        ]);
    }

    #[Route('/new/{idS}', name: 'abonnement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, $idS): Response
    {
        // Retrieve the Salle entity based on the provided ID
        $salle = $entityManager->getRepository(Salle::class)->find($idS);

        // Check if the Salle entity exists
        if (!$salle) {
            throw $this->createNotFoundException('Salle not found for id ' . $idS);
        }

        $abonnement = new Abonnement();
        $abonnement->setSalle($salle); // Set the Salle entity to the Abonnement

        $form = $this->createForm(AbonnementType::class, $abonnement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the Abonnement
            $entityManager->persist($abonnement);
            $entityManager->flush();

            return $this->redirectToRoute('abonnement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('abonnement/new.html.twig', [
            'abonnement' => $abonnement,
            'form' => $form,
        ]);
    }

    #[Route('/{idS}', name: 'abonnement_show', methods: ['GET'])]
    public function show(Abonnement $abonnement): Response
    {
        return $this->render('abonnement/show.html.twig', [
            'abonnement' => $abonnement,
        ]);
    }

    #[Route('/{idS}/edit', name: 'abonnement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Abonnement $abonnement): Response
    {
        $form = $this->createForm(AbonnementType::class, $abonnement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('abonnement_index');
        }

        return $this->render('abonnement/edit.html.twig', [
            'abonnement' => $abonnement,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{idS}', name: 'abonnement_delete', methods: ['POST'])]
    public function delete(Request $request, Abonnement $abonnement): Response
    {
        if ($this->isCsrfTokenValid('delete'.$abonnement->getIdS(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($abonnement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('abonnement_index');
    }
}

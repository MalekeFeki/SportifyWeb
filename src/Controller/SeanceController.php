<?php

namespace App\Controller;

use App\Entity\Seance;
use App\Entit\Salle;
use App\Form\SeanceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response; // Import Response class
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

#[Route('/seance')]
class SeanceController extends AbstractController
{
    #[Route('/', name: 'app_seance_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $seances = $entityManager
            ->getRepository(Seance::class)
            ->findAll();

        return $this->render('seance/index.html.twig', [
            'seances' => $seances,
        ]);
    }

    #[Route('/new/{idS}', name: 'app_seance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, $idS): Response
    {
        // Retrieve the Salle entity based on the provided ID
        $salle = $entityManager->getRepository(Salle::class)->find($idS);

        // Check if the Salle entity exists
        if (!$salle) {
            throw $this->createNotFoundException('Salle not found for id ' . $idS);
        }

        $seance = new Seance();
        $seance->setSalle($salle); // Set the Salle entity to the Seance

        $form = $this->createForm(SeanceType::class, $seance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the Seance
            $entityManager->persist($seance);
            $entityManager->flush();

            return $this->redirectToRoute('app_seance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('seance/new.html.twig', [
            'seance' => $seance,
            'form' => $form,
        ]);
    }

    #[Route('/{idseance}', name: 'app_seance_show', methods: ['GET'])]
    public function show(Seance $seance): Response
    {
        return $this->render('seance/show.html.twig', [
            'seance' => $seance,
        ]);
    }

    #[Route('/{idseance}/edit', name: 'app_seance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Seance $seance, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SeanceType::class, $seance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_seance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('seance/edit.html.twig', [
            'seance' => $seance,
            'form' => $form,
        ]);
    }

    #[Route('/{idseance}', name: 'app_seance_delete', methods: ['POST'])]
    public function delete(Request $request, Seance $seance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$seance->getIdseance(), $request->request->get('_token'))) {
            $entityManager->remove($seance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_seance_index', [], Response::HTTP_SEE_OTHER);
    }
}

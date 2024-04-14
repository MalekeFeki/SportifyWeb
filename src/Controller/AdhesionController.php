<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Salle;
use App\Entity\Adhesion;
use App\Repository\AdhesionRepository;
use App\Form\AdhesionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/adhesion')]
class AdhesionController extends AbstractController
{
    #[Route('/', name: 'app_adhesion_index', methods: ['GET'])]
    public function index(AdhesionRepository $adhesionRepository): Response
    {
        return $this->render('adhesion/index.html.twig', [
            'adhesions' => $adhesionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_adhesion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Retrieve the current user
        $user = $entityManager->getRepository(User::Class)->find(1);
        $salle = $entityManager->getRepository(Salle::class)->find(1);
        
        // Create a new Adhesion instance and set the user
        $adhesion = new Adhesion();
        $adhesion->setId($user);

        // Assuming you have a gym ID available, set it for the adhesion
        $adhesion->setGymId($salle);

        // Create the form
        $form = $this->createForm(AdhesionType::class, $adhesion);
        $form->handleRequest($request);

        // Handle form submission
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($adhesion);
            $entityManager->flush();

            return $this->redirectToRoute('app_adhesion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('adhesion/new.html.twig', [
            'adhesion' => $adhesion,
            'form' => $form,
        ]);
    }

    #[Route('/{ida}', name: 'app_adhesion_show', methods: ['GET'])]
    public function show(Adhesion $adhesion): Response
    {
        return $this->render('adhesion/show.html.twig', [
            'adhesion' => $adhesion,
        ]);
    }

    #[Route('/{ida}/edit', name: 'app_adhesion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Adhesion $adhesion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdhesionType::class, $adhesion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_adhesion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('adhesion/edit.html.twig', [
            'adhesion' => $adhesion,
            'form' => $form,
        ]);
    }

    #[Route('/{ida}', name: 'app_adhesion_delete', methods: ['POST'])]
    public function delete(Request $request, Adhesion $adhesion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adhesion->getIda(), $request->request->get('_token'))) {
            $entityManager->remove($adhesion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_adhesion_index', [], Response::HTTP_SEE_OTHER);
    }
}

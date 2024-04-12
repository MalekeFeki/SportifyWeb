<?php

namespace App\Controller;

use App\Entity\CoachAdmin;
use App\Form\CoachAdminType;
use App\Repository\CoachAdminRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/coach/admin')]
class CoachAdminController extends AbstractController
{
    #[Route('/', name: 'app_coach_admin_index', methods: ['GET'])]
    public function index(CoachAdminRepository $coachRepository): Response
    {
        $coachs = $coachRepository->findAll();

        return $this->render('coach_admin/index.html.twig', [
            'coachs' => $coachs,
        ]);
    }

    #[Route('/new', name: 'app_coach_admin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $coach = new CoachAdmin();
        $form = $this->createForm(CoachAdminType::class, $coach);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($coach);
            $entityManager->flush();

            return $this->redirectToRoute('app_coach_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('coach_admin/new.html.twig', [
            'coach' => $coach,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_coach_admin_show', methods: ['GET'])]
    public function show(CoachAdmin $coach): Response
    {
        return $this->render('coach_admin/show.html.twig', [
            'coach' => $coach,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_coach_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CoachAdmin $coach, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CoachAdminType::class, $coach);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_coach_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('coach_admin/edit.html.twig', [
            'coach' => $coach,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_coach_admin_delete', methods: ['POST'])]
    public function delete(Request $request, CoachAdmin $coach, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$coach->getId(), $request->request->get('_token'))) {
            $entityManager->remove($coach);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_coach_admin_index', [], Response::HTTP_SEE_OTHER);
    }
}

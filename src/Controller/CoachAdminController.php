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
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/coach/admin')]
class CoachAdminController extends AbstractController
{
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
        // Extrait la photo du formulaire
        $photoFile = $form->get('photo')->getData();

        // Gérer l'upload de la photo
        if ($photoFile) {
            $newFilename = uniqid().'.'.$photoFile->guessExtension();
            try {
                $photoFile->move(
                    $this->getParameter('photo_directory'),
                    $newFilename
                );
                $coach->setPhoto($newFilename); // Enregistre le nom du fichier dans l'entité CoachAdmin
            } catch (FileException $e) {
                // Gérer les erreurs d'upload
            }
        }

        $entityManager->persist($coach);
        $entityManager->flush();

        return $this->redirectToRoute('app_coach_admin_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('coach_admin/new.html.twig', [
        'form' => $form->createView(), // Assurez-vous de passer le formulaire à Twig
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

        return $this->render('coach_admin/edit.html.twig', [
            'coach' => $coach,
            'form' => $form->createView(),
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

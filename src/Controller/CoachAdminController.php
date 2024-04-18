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
        $coach_admins = $coachRepository->findAll();
    
        return $this->render('coach_admin/index.html.twig', [
            'coach_admins' => $coach_admins, // Change 'coachs' to 'coach_admins'
        ]);
    }
    
    
    #[Route('/new', name: 'app_coach_admin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, CoachAdminRepository $coachAdminRepository): Response
    {
        $coach_admins = $coachAdminRepository->findAll(); // Récupérez les coachs admins si nécessaire
    
        $coach_admin = new CoachAdmin();
        $form = $this->createForm(CoachAdminType::class, $coach_admin);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier si un coach avec le même nom et prénom existe déjà
            $formData = $form->getData();
            $existingCoach = $coachAdminRepository->findOneBy([
                'nom' => $formData->getNom(),
                'prenom' => $formData->getPrenom()
            ]);
    
            if ($existingCoach) {
                $this->addFlash('error', 'Un coach avec le même nom et prénom existe déjà.');
                return $this->redirectToRoute('app_coach_admin_new');
            }
    
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
                    $coach_admin->setPhoto($newFilename); // Enregistre le nom du fichier dans l'entité CoachAdmin
                } catch (FileException $e) {
                    // Gérer les erreurs d'upload
                }
            }
    
            $entityManager->persist($coach_admin);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_coach_admin_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('coach_admin/new.html.twig', [
            'form' => $form->createView(),
            'coach_admins' => $coach_admins, // Passer les coachs admins au template
        ]);
    }
    

    #[Route('/{id}', name: 'app_coach_admin_show', methods: ['GET'])]
    public function show(CoachAdmin $coach_admins): Response
    {
        return $this->render('coach_admin/show.html.twig', [
            'coach' => $coach_admins,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_coach_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CoachAdmin $coach_admins, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CoachAdminType::class, $coach_admins);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Extrait la photo du formulaire
            $photoFile = $form->get('photo')->getData();

            // Gérer l'upload de la photo si un nouveau fichier est téléchargé
            if ($photoFile) {
                $newFilename = uniqid().'.'.$photoFile->guessExtension();
                try {
                    $photoFile->move(
                        $this->getParameter('photo_directory'),
                        $newFilename
                    );
                    $coach_admins->setPhoto($newFilename); // Enregistre le nom du fichier dans l'entité CoachAdmin
                } catch (FileException $e) {
                    // Gérer les erreurs d'upload
                }
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_coach_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('coach_admin/edit.html.twig', [
            'coach_admins' => $coach_admins, // Correction ici
            'form' => $form->createView(),
        ]);
    }


    #[Route('/{id}', name: 'app_coach_admin_delete', methods: ['POST'])]
    public function delete(Request $request, CoachAdmin $coach_admins, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$coach_admins->getId(), $request->request->get('_token'))) {
            $entityManager->remove($coach_admins);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_coach_admin_index', [], Response::HTTP_SEE_OTHER);
    }
}
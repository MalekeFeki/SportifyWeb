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
use Symfony\Component\Form\Extension\Core\Type\TextType;

#[Route('/coach/admin')]
class CoachAdminController extends AbstractController
{
    #[Route('/', name: 'app_coach_stats', methods: ['GET'])]
    public function index(Request $request, CoachAdminRepository $coachAdminRepository): Response
    {
        $letter = $request->query->get('letter');
        $coach_admins = [];
    
        if ($letter) {
            // Effectuer la recherche en fonction de la lettre sélectionnée
            $coach_admins = $coachAdminRepository->findByFirstLetter($letter);
        } else {
            // Afficher tous les coachs si aucune lettre n'est sélectionnée
            $coach_admins = $coachAdminRepository->findAll();
        }
    
        return $this->render('coach_admin/index.html.twig', [
            'coach_admins' => $coach_admins,
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
            } else {
                // Set the photo attribute even if no new photo is uploaded
                if (!$photoFile && $coach_admin->getPhoto()) {
                    // Preserve the existing photo if no new one is uploaded
                    $coach_admin->setPhoto($coach_admin->getPhoto());
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
   
    public function show(Request $request, CoachAdmin $coach_admins, CoachAdminRepository $coachAdminRepository): Response
    {
        // Créer le formulaire de recherche
        $searchForm = $this->createFormBuilder(null, ['attr' => ['class' => 'form-inline']])
            ->add('nom', TextType::class, ['required' => false, 'label' => 'Nom', 'attr' => ['class' => 'form-control mr-sm-2', 'placeholder' => 'Nom']])
            ->add('prenom', TextType::class, ['required' => false, 'label' => 'Prénom', 'attr' => ['class' => 'form-control mr-sm-2', 'placeholder' => 'Prénom']])
            ->getForm();

        // Gérer la soumission du formulaire
        $searchForm->handleRequest($request);

        // Si le formulaire est soumis et valide, effectuez la recherche
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $searchData = $searchForm->getData();
            $searchNom = $searchData['nom'];
            $searchPrenom = $searchData['prenom'];

            // Effectuer la recherche dans votre repository et récupérer les résultats
            $searchResults = $coachAdminRepository->findByNomAndPrenom($searchNom, $searchPrenom);
        } else {
            // Si le formulaire n'est pas soumis ou n'est pas valide, laissez $searchResults vide
            $searchResults = [];
        }

        // Passer le formulaire et les résultats de la recherche à la vue Twig
        return $this->render('coach_admin/show.html.twig', [
            'coach_admin' => $coach_admins,
            'searchForm' => $searchForm->createView(),
            'searchResults' => $searchResults,
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
    
    #[Route('/stats', name: 'app_coach_stats')]
    public function stats(CoachAdminRepository $coachAdminRepository): Response
    {
        $coachCount = $coachAdminRepository->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->getQuery()
            ->getSingleScalarResult();
         $maleCoachCount = $coachAdminRepository->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.sexe = :sexe')
            ->setParameter('sexe', 'HOMME')
            ->getQuery()
            ->getSingleScalarResult();

            $femaleCoachCount = $coachAdminRepository->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.sexe = :sexe')
            ->setParameter('sexe', 'FEMME')
            ->getQuery()
            ->getSingleScalarResult();
    
        // Autres calculs de statistiques
        // Fetch coach admins if necessary
    $coach_admins = $coachAdminRepository->findAll(); // Fetch coach admins if necessary
    
        return $this->render('coach_admin/stat.html.twig', [
            'coachCount' => $coachCount,
            'maleCoachCount' => $maleCoachCount,
            'femaleCoachCount' => $femaleCoachCount,
            'coach_admins' => $coach_admins,
            // Autres données de statistiques à passer au template
        ]);
    }
}

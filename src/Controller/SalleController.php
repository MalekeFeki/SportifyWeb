<?php

namespace App\Controller;

use App\Entity\Salle;
use App\Entity\Seance;
use App\Form\SeanceType;
use App\Form\SalleType;
use App\Repository\SalleRepository;
use App\Repository\SeanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Knp\Component\Pager\PaginatorInterface;



#[Route('/salle')]
class SalleController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(Request $request, SalleRepository $salleRepository, PaginatorInterface $paginator): Response
    {
        $regionFilter = $request->query->get('region');
        $currentPage = $request->request->getInt('page', 1); 
        $searchTerm = $request->query->get('search');
        $pageSize = 2; // Set the number of items per page
        $pagination = null;
        // Retrieve all salles from repository

        if ($regionFilter) {
            $pagination = $salleRepository->searchByName($regionFilter, $currentPage, $pageSize);
        }
        // Apply search filter if provided
        elseif ($searchTerm) {
            $pagination = $salleRepository->searchByName($searchTerm, $currentPage, $pageSize);
        }else{
            $pagination = $salleRepository->paginate($currentPage, $pageSize);
        }

         // Calculate the total number of pages  
         $totalItems = $pagination->count(); // Total number of items
         $totalPages = ceil($totalItems / $pageSize); // Calculate total pages
        // Paginate the results
        

        return $this->render('salle/index.html.twig', [
            'totalPages' => $totalPages,
             // Pass currentPage to the template
             'currentPage' =>  $currentPage,
            'pagination' => $pagination,
            
        ]);
    }







    #[Route('/new', name: 'app_salle_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $salle = new Salle();
        $form = $this->createForm(SalleType::class, $salle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle file upload
            $file = $form['imageSalle']->getData();
            if ($file instanceof UploadedFile) { // Check if file is an instance of UploadedFile
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();

                // Move the file to the directory where you want to store it
                $file->move(
                    $this->getParameter('salle_images_directory'),
                    $fileName
                );

                // Set the file name to the Salle entity
                $salle->setImageSalle($fileName); // Correct method name
            }

            $entityManager->persist($salle);
            $entityManager->flush();

            return $this->redirectToRoute('app_salle_show', ['idS' => $salle->getIdS()]);
        }

        return $this->renderForm('salle/new.html.twig', [
            'salle' => $salle,
            'form' => $form,
        ]);
    }


    #[Route('/{idS}', name: 'app_salle_show', methods: ['GET'])]
    public function show(Salle $salle, SeanceRepository $seanceRepository): Response // Inject SeanceRepository
    {
        // // Calculate statistics about seances for this salle
        // $totalSeances = $seanceRepository->countSeancesBySalle($salle);

        return $this->render('salle/show.html.twig', [
            'salle' => $salle,
            // 'totalSeances' => $totalSeances,
        ]);
    }

    #[Route('/{idS}/edit', name: 'app_salle_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Salle $salle, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SalleType::class, $salle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle file upload
            $file = $form['imageSalle']->getData();
            if ($file instanceof UploadedFile) {
                $fileName = uniqid() . '.' . $file->guessExtension();
                $file->move(
                    $this->getParameter('salle_images_directory'),
                    $fileName
                );
                $salle->setImageSalle($fileName);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_salle_show', ['idS' => $salle->getIdS()]);
        }

        return $this->renderForm('salle/edit.html.twig', [
            'salle' => $salle,
            'form' => $form,
        ]);
    }

    #[Route('/{idS}', name: 'app_salle_delete', methods: ['POST'])]
    public function delete(Request $request, Salle $salle, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $salle->getIds(), $request->request->get('_token'))) {
            $entityManager->remove($salle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/search-salle', name: 'search_salle', methods: ['POST'])]
    public function searchSalle(Request $request, SalleRepository $salleRepository): Response
    {
        $data = json_decode($request->getContent(), true);
        $nom = $data['nom'];

        // Query database for salles matching the entered nom
        $matchingSalles = $salleRepository->findByNom($nom);

        // Serialize matching salles to JSON format
        $response = [];
        foreach ($matchingSalles as $salle) {
            $response[] = [
                'nom' => $salle->getNom(),
                // Add other fields as needed
            ];
        }

        return $this->json($response);
    }

    //     // #[Route('/newseance/{idS}', name: '')]
    // public function newseance(Salle $salle): Response
    // {
    //     return $this->redirectToRoute('app_seance_new', ['idS' => $salle->getIdS()]);
    // }










    // #[Route('/{id}/seance/new', name: 'salle_new_seance', methods: ['GET', 'POST'])]
    // public function newSeance(Request $request, Salle $salle): Response
    // {
    //     $seance = new Seance();
    //     $form = $this->createForm(SeanceType::class, $seance);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $seance->setSalle($salle);
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->persist($seance);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_salle_show', ['ids' => $salle->getIds()]);
    //     }

    //     return $this->render('seance/new.html.twig', [
    //         'salle' => $salle,
    //         'form' => $form->createView(),
    //     ]);
    // }




    // #[Route('/{id}/seance/new', name: 'salle_new_seance', methods: ['GET', 'POST'])]
    // public function newSeance(Request $request, Salle $salle): Response
    // {
    //     $seance = new Seance();
    //     $form = $this->createForm(SeanceType::class, $seance);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         // Set the current Salle to the Seance
    //         $seance->setSalle($salle);

    //         // Persist the Seance
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->persist($seance);
    //         $entityManager->flush();

    //         // Redirect to the Salle show page
    //         return $this->redirectToRoute('app_salle_show', ['ids' => $salle->getIds()]);
    //     }

    //     return $this->render('seance/new.html.twig', [
    //         'salle' => $salle,
    //         'form' => $form->createView(),
    //     ]);
    // }





    //     #[Route('/{id}/seance/new', name: 'salle_new_seance', methods: ['GET', 'POST'])]
    // public function newSeance(Request $request, idS $salle): Response
    // {
    //     $seance = new Seance();
    //     $form = $this->createForm(SeanceType::class, $seance);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         // Set the current Salle to the Seance
    //         $seance->setIdS($salle);

    //         // Persist the Seance
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->persist($seance);
    //         $entityManager->flush();

    //         // Redirect to the Salle show page
    //         return $this->redirectToRoute('app_salle_show', ['idS' => $salle->getIdS()]);
    //     }

    //     return $this->render('seance/new.html.twig', [
    //         'salle' => $salle,
    //         'form' => $form->createView(),
    //     ]);
    // }

}

<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/event')]
class EvenementController extends AbstractController
{
    #[Route('/', name: 'app_evenement_index', methods: ['GET'])]
    public function index(EvenementRepository $evenementRepository): Response
    {
        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenementRepository->findAll(),
        ]);
    }

    #[Route('/allevent', name: 'allevent', methods: ['GET'])]
    public function allevent(Request $request, EvenementRepository $evenementRepository, PaginatorInterface $paginator): Response
    {
        $searchQuery = $request->query->get('query');
        // Retrieve all events query
        $currentPage = $request->query->getInt('page', 1); // Get the current page from the request, default to 1 if not provided
        $pageSize = 4; // Set the number of items per page
        if ($searchQuery) {
            // Search for events by name
            $pagination = $evenementRepository->searchByName($searchQuery, $currentPage, $pageSize);
            // $pagination = $evenementRepository->paginate($currentPage, $pageSize);
        } else {
            $pagination = $evenementRepository->paginate($currentPage, $pageSize);
        }
        // Calculate the total number of pages  
        $totalItems = $pagination->count(); // Total number of items
        $totalPages = ceil($totalItems / $pageSize); // Calculate total pages
        // Count events for each category
        $categoryCounts = $evenementRepository->countEventsByCategory();

        return $this->render('evenement/allevent.html.twig', [
            'currentPage' => $currentPage ?? null,
            'pagination' => $pagination ?? null,
            'totalPages' => $totalPages ?? null,
            'categoryCounts' => $categoryCounts,
            'category' => "",
            'searchQuery' => $searchQuery,
            'evenements' => $events ?? null,
        ]);
    }
    #[Route('/allevent/{category}', name: 'allevent_by_category', methods: ['GET'])]
    public function alleventbycategory(Request $request, EvenementRepository $evenementRepository, PaginatorInterface $paginator, $category): Response
    {
        $searchQuery = $request->query->get('query');
        // Retrieve all events query filtered by category
        $currentPage = $request->query->getInt('page', 1);
        $pageSize = 4;
        if ($searchQuery) {
            // Search for events by name
            $pagination = $evenementRepository->searchByName($searchQuery, $currentPage, $pageSize);
        } else {
            $pagination = $evenementRepository->paginateByCategory($category, $currentPage, $pageSize);
        }
        // Calculate the total number of pages
        $totalItems = $pagination->count();
        $totalPages = ceil($totalItems / $pageSize);

        // Count events for each category
        $categoryCounts = $evenementRepository->countEventsByCategory();

        return $this->render('evenement/allevent.html.twig', [
            'currentPage' => $currentPage ?? null,
            'pagination' => $pagination ?? null,
            'totalPages' => $totalPages ?? null,
            'categoryCounts' => $categoryCounts,
            'searchQuery' => $searchQuery,
            'evenements' => $events ?? null,
            'category' => $category, // Pass the category to the template
        ]);
    }
    #[Route('/{idevent}', name: 'app_evenement_show', methods: ['GET'])]
    public function show(Evenement $evenement): Response
    {

        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }
    // --------------------------admin-----------------------------------------------
    #[Route('/admin/new', name: 'app_evenement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ManagerRegistry $entityManager): Response
    {
        // Create a new instance of Evenement entity
        $event = new Evenement();
        $entityManager = $entityManager->getManager();
        // Create a form instance and handle the request
        $form = $this->createForm(EvenementType::class, $event);
        $form->handleRequest($request);

        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            $photolink = $form->get('photo')->getData();
            $event->setPhoto($photolink);
            // Combine selected hours and minutes into HH:SS format
            $heureev_hours = $form->get('heureev_hours')->getData();
            $heureev_minutes = $form->get('heureev_minutes')->getData();
            $heureev = sprintf('%02d:%02d:00', $heureev_hours, $heureev_minutes);

            // Set the heureev property in the entity
            $event->setHeureev($heureev);

            $entityManager->persist($event);
            $entityManager->flush();
            return $this->redirectToRoute('app_evenement_index');
        }

        // Render the form
        return $this->renderForm('evenement/new.html.twig', [
            'form' => $form,
        ]);
    }



    #[Route('/{idevent}/edit', name: 'app_evenement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $time = new \DateTime($evenement->getHeureev());
        // Extract hours and minutes from the DateTime object
        $hours = (int)$time->format('H');
        $minutes = (int)$time->format('i');
        $evenement->setHeureevHours($hours);
        $evenement->setHeureevMinutes($minutes);
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Combine selected hours and minutes into HH:SS format
            $photolink = $form->get('photo')->getData();

            $evenement->setPhoto($photolink);

            $heureev_hours = $form->get('heureev_hours')->getData();
            $heureev_minutes = $form->get('heureev_minutes')->getData();
            $heureev = sprintf('%02d:%02d:00', $heureev_hours, $heureev_minutes);
            $evenement->setHeureev($heureev);
            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{idevent}', name: 'app_evenement_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        // Delete associated eventreservation records
        $eventReservations = $evenement->getEventreservations();
        foreach ($eventReservations as $eventReservation) {
            $entityManager->remove($eventReservation);
        }
        if ($this->isCsrfTokenValid('delete' . $evenement->getIdevent(), $request->request->get('_token'))) {

            $entityManager->remove($evenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
    }
    public function increaseInterest($idevent, EntityManagerInterface $entityManager): Response
{
    // Get the event by its ID and increment the number of interested people
    $event = $entityManager->getRepository(Evenement::class)->find($idevent);
    if ($event) {
        $event->setNombrepersonneinteresse($event->getNombrepersonneinteresse() + 1);
        $entityManager->flush();

        // Return JSON response with updated interest count
        return new JsonResponse(['interested' => true, 'newInterestCount' => $event->getNombrepersonneinteresse()]);
    }

    // Return JSON response indicating failure
    return new JsonResponse(['error' => 'Event not found'], Response::HTTP_NOT_FOUND);
}
    public function makeReservation(Evenement $evenement): Response
    {
        // Redirect to the reservation page
        // You can directly redirect to the reservation page or perform additional logic here if needed
        return $this->redirectToRoute('app_eventreservation_new', ['eventid' => $evenement->getIdevent()]);
    }
}

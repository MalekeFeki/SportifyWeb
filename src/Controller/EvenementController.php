<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Eventreservation;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use App\Repository\EventreservationRepository;
use App\Service\PdfService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Pyrrah\OpenWeatherMapBundle\Services\Client;
use Symfony\Component\HttpClient\HttpClient;

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

    public function showReservations(int $idevent, EntityManagerInterface $entityManager, EventreservationRepository $eventreservationRepository): Response
    {
        $evenement = $entityManager->getRepository(Evenement::class)->find($idevent);
        $reservations = $eventreservationRepository->findBy(['eventid' => $idevent]);

        return $this->render('evenement/show_reservations.html.twig', [
            'evenement' => $evenement,
            'reservations' => $reservations,
        ]);
    }
    #[Route('', name: 'searchdoo', methods: ['POST'])]
    public function search(Request $request, EvenementRepository $evenementRepository, PaginatorInterface $paginator): Response
    {
        $searchQuery = $request->request->get('query');
        $currentPage = $request->request->getInt('page', 1); // Get the current page from the request, default to 1 if not provided
        $pageSize = 4; // Set the number of items per page
        $pagination = null;

        if ($searchQuery) {
            // Search for events by name
            $pagination = $evenementRepository->searchByName($searchQuery, $currentPage, $pageSize);
        } else {
            // Perform the normal pagination
            $pagination = $evenementRepository->paginate($currentPage, $pageSize);
        }

        // Calculate the total number of pages  
        $totalItems = $pagination->count(); // Total number of items
        $totalPages = ceil($totalItems / $pageSize); // Calculate total pages
        $categoryCounts = $evenementRepository->countEventsByCategory();
        // Render the pagination HTML
        $paginationHtml = $this->renderView('evenement/allevent_search.html.twig', [
            'currentPage' => $currentPage, // Pass currentPage to the template
            'pagination' => $pagination,
            'totalPages' => $totalPages,
            'categoryCounts' => $categoryCounts,
        ]);

        // Return the pagination HTML as JSON response
        return new JsonResponse([
            'html' => $paginationHtml, // Include the HTML content
            'pagination' => $pagination,
        ]);
    }



    #[Route('/allevent', name: 'allevent', methods: ['GET'])]
    public function allevent(Request $request, EvenementRepository $evenementRepository, PaginatorInterface $paginator): Response
    {

        
        // Retrieve search query from the request
        $searchQuery = $request->query->get('query');

        // Retrieve the current page from the request, default to 1 if not provided
        $currentPage = $request->query->getInt('page', 1);

        // Set the number of items per page
        $pageSize = 4;

        // Initialize pagination object
        $pagination = null;

        // If search query is provided, perform search
        if ($searchQuery) {
            // Search for events by name
            $pagination = $evenementRepository->searchByName($searchQuery, $currentPage, $pageSize);
        } else {
            // Retrieve all events with pagination
            $pagination = $evenementRepository->paginate($currentPage, $pageSize);
        }

        // Calculate the total number of items
        $totalItems = $pagination->count();

        // Calculate total pages
        $totalPages = ceil($totalItems / $pageSize);

        // Count events for each category
        $categoryCounts = $evenementRepository->countEventsByCategory();

        // Render the view with pagination data
        return $this->render('evenement/allevent.html.twig', [
            'currentPage' => $currentPage, // Pass currentPage to the template
            'pagination' => $pagination,
            'totalPages' => $totalPages,
            'categoryCounts' => $categoryCounts,
            'category' => "",
            'searchQuery' => $searchQuery,
        ]);
    }


    // #[Route('/search-events', name: 'search_events', methods: ['GET'])]
    // public function searchEvents(Request $request, EvenementRepository $evenementRepository, PaginatorInterface $paginator): Response
    // {
    //     $searchQuery = $request->query->get('query');
    //     $currentPage = $request->query->getInt('page', 1);
    //     $pageSize = 4;

    //     // Perform search query using the repository
    //     $results = $evenementRepository->searchByName($searchQuery,$currentPage,$pageSize);

    //     // Paginate the search results
    //     $pagination = $paginator->paginate(
    //         $results,
    //         $currentPage,
    //         $pageSize
    //     );

    //     // Return search results as JSON response
    //     return $this->json($pagination);
    // }
    // #[Route('/allevent', name: 'allevent', methods: ['GET'])]
    // public function allevent(Request $request, EvenementRepository $evenementRepository, PaginatorInterface $paginator): Response
    // {
    //     $searchQuery = $request->query->get('query');
    //     // Retrieve all events query
    //     $currentPage = $request->query->getInt('page', 1); // Get the current page from the request, default to 1 if not provided
    //     $pageSize = 4; // Set the number of items per page
    //     if ($searchQuery) {
    //         // Search for events by name
    //         $pagination = $evenementRepository->searchByName($searchQuery, $currentPage, $pageSize);
    //         // $pagination = $evenementRepository->paginate($currentPage, $pageSize);
    //     } else {
    //         $pagination = $evenementRepository->paginate($currentPage, $pageSize);
    //     }
    //     // Calculate the total number of pages  
    //     $totalItems = $pagination->count(); // Total number of items
    //     $totalPages = ceil($totalItems / $pageSize); // Calculate total pages
    //     // Count events for each category
    //     $categoryCounts = $evenementRepository->countEventsByCategory();

    //     return $this->render('evenement/allevent.html.twig', [
    //         'currentPage' => $currentPage ?? null,
    //         'pagination' => $pagination ?? null,
    //         'totalPages' => $totalPages ?? null,
    //         'categoryCounts' => $categoryCounts,
    //         'category' => "",
    //         'searchQuery' => $searchQuery,
    //         'evenements' => $events ?? null,
    //     ]);
    // }
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
    public function show(Evenement $evenement,Client $weatherClient): Response
    {
        // Get latitude and longitude from the event
    $latitude = $evenement->getLat();
    $longitude = $evenement->getLon();

    // Get the start date of the event
    $startDate = $evenement->getDatedDebutEV()->format('Y-m-d');

    // Your WeatherAPI.com API key
    $apiKey = 'cb88a4d536274914ac9180047242904';

    // Prepare the API URL with the required parameters
    $url = sprintf(
        'http://api.weatherapi.com/v1/current.json?q=%s,%s&key=%s&dt=%s',
        $latitude,
        $longitude,
        $apiKey,
        $startDate
    );
    $httpClient = HttpClient::create();
    // Make a GET request to the API
    $response = $httpClient->request('GET', $url);

    // Decode the JSON response
    $weatherData = $response->toArray();

    // Handle the weather data as needed
    // Example: Extract temperature
    $temperature = $weatherData['current']['temp_c'];

    // Example: Extract weather condition
    $weatherCondition = $weatherData['current']['condition']['text'];
    $localTime = $weatherData['location']['localtime'];


    
    



        // Get the current date and time
        $currentDateTime = new \DateTime();

        // Check if the event is happening now or has already passed
        $isEventPassed = $currentDateTime > $evenement->getDatedFinEV();
        $isEventHappeningNow = $currentDateTime >= $evenement->getDatedDebutEV() && $currentDateTime <= $evenement->getDatedFinEV();
        // Check if the event capacity is greater than 0
        $isCapacityGreaterThanZero = $evenement->getCapacite() > 0;
        // Format the DateTime objects as strings
        // $location = $evenement->getLat() . ',' . $evenement->getLon();
        
        // $weather = $weatherClient->getWeather($location);
        $formattedDatedDebutEV = $evenement->getDatedDebutEV()->format('Y-m-d');
        $formattedDatedFinEV = $evenement->getDatedFinEV()->format('Y-m-d');
        $formattedHeureEV = $evenement->getHeureEV();
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
            'formattedDatedDebutEV' => $formattedDatedDebutEV,
            'formattedDatedFinEV' => $formattedDatedFinEV,
            'formattedHeureEV' => $formattedHeureEV,
            'isCapacityGreaterThanZero' => $isCapacityGreaterThanZero,
            'isEventPassed' => $isEventPassed,
            'isEventHappeningNow' => $isEventHappeningNow,
            'temperature' => $temperature,
            'weatherCondition' => $weatherCondition, // Pass weather data to the template
            'localTime'=>$localTime ,

        ]);
    }
    // --------------------------admin-----------------------------------------------
    #[Route('/admin/new', name: 'app_evenement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ManagerRegistry $entityManager,FlashyNotifier $flashy): Response
    {
        // Create a new instance of Evenement entity
        $event = new Evenement();
        $entityManager = $entityManager->getManager();
        // Create a form instance and handle the request
        $form = $this->createForm(EvenementType::class, $event);
        $form->handleRequest($request);

        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            $flashy->success('found cin !', '');
            $existingEvent = $entityManager->getRepository(Evenement::class)->findOneBy(['nomev' => $event->getNomev()]);
            if ($existingEvent) {
                $form->get('nomev')->addError(new \Symfony\Component\Form\FormError('Event name must be unique.'));
                return $this->renderForm('evenement/new.html.twig', [
                    'form' => $form,
                ]);
            }
            // Set latitude and longitude from the form
            $event->setLat($form->get('lat')->getData());
            $event->setLon($form->get('lon')->getData());

            $photoFile = $form->get('photo')->getData(); // Retrieve the uploaded file object

            if ($photoFile) {
                // Generate a unique name for the file to prevent overwriting existing files
                $newFilename = uniqid() . '.' . $photoFile->guessExtension();
                $newFilename = "upload_directory/" . $newFilename;
                // Move the file to the desired directory
                try {
                    $photoFile->move(
                        $this->getParameter('upload_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle the exception if unable to move the file
                    // For example, you can add flash messages or log the error
                }

                // Set the file name (or path) in your entity
                $event->setPhoto($newFilename);
            }

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
            $existingEvent = $entityManager->getRepository(Evenement::class)->findOneBy(['nomev' => $evenement->getNomev()]);
            $namenow = $form->get('nomev')->getData();
            if ($existingEvent == $namenow) {
                if ($existingEvent) {
                    $form->get('nomev')->addError(new \Symfony\Component\Form\FormError('Event name must be unique.'));
                    return $this->renderForm('evenement/new.html.twig', [
                        'form' => $form,
                    ]);
                }
            }

            $photoFile = $form->get('photo')->getData(); // Retrieve the uploaded file object

            if ($photoFile) {
                // Generate a unique name for the file to prevent overwriting existing files
                $newFilename = uniqid() . '.' . $photoFile->guessExtension();
                $newFilename = "upload_directory/" . $newFilename;
                // Move the file to the desired directory
                try {
                    $photoFile->move(
                        $this->getParameter('upload_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                // Set the file name (or path) in your entity
                $evenement->setPhoto($newFilename);
            }


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

    
    public function increaseInterest($idevent, Request $request, EntityManagerInterface $entityManager): JsonResponse
{
    // Get the event by its ID
    $event = $entityManager->getRepository(Evenement::class)->find($idevent);
    
    if (!$event) {
        return new JsonResponse(['error' => 'Event not found'], JsonResponse::HTTP_NOT_FOUND);
    }
    
    // Get the current interest count
    $currentInterestCount = $event->getNombrepersonneinteresse();
    
    // Check if the user is currently interested based on the request data
    $isInterested = $request->request->get('isInterested');
    
    if ($isInterested) {
        // If the user is currently interested, decrement the interest count
        $event->setNombrepersonneinteresse($currentInterestCount - 1);
    } else {
        // If the user is not currently interested, increment the interest count
        $event->setNombrepersonneinteresse($currentInterestCount + 1);
    }
    
    // Persist changes to the database
    $entityManager->flush();
    
    // Return JSON response with updated interest count
    return new JsonResponse(['interested' => !$isInterested, 'newInterestCount' => $event->getNombrepersonneinteresse()]);
}

    public function makeReservation(Evenement $evenement): Response
    {
        // Redirect to the reservation page
        // You can directly redirect to the reservation page or perform additional logic here if needed
        return $this->redirectToRoute('app_eventreservation_new', ['eventid' => $evenement->getIdevent()]);
    }


    #[Route('/event/{idevent}/pdf', name: 'event_pdf')]
    public function generateEventPdfnow(Evenement $evenement, PdfService $pdfService,FlashyNotifier $flashy): Response
    {
        $name= $evenement->getNomev();
        // Generate PDF for the event
        $pdfService->printEvent($evenement,$name);
        $flashy->success('done', '');

        // Optionally, you can return a response or redirect
        // For example, redirect back to the event details page
        return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        
    }
}

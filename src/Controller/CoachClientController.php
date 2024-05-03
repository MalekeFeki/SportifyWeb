<?php

namespace App\Controller;

use App\Entity\CoachClient;
use App\Form\CoachClientType;
use App\Repository\CoachAdminRepository;
use App\Repository\CoachClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[Route('/coach/client')]
class CoachClientController extends AbstractController
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    #[Route('/', name: 'app_coach_client_index', methods: ['GET'])]
    #[Route('/', name: 'app_coach_client_index', methods: ['GET'])]
    public function index(CoachClientRepository $coachClientRepository, CoachAdminRepository $coachAdminRepository): Response
    {
        // Supposez que vous avez un moyen de déterminer le remainingTime, par exemple en récupérant la valeur à partir de quelque part
        $remainingTime = 20; // Définir la valeur de remainingTime
    
        // Comptez le nombre de commentaires modifiés
        $clickedCount = count($this->session->get('edited_comments', []));
    
        // Récupérez tous les coachs avec leurs photos
        $coach_admins = $coachAdminRepository->findAllWithPhotos(); // Change to findAllWithPhotos() if that's your method name
    
        // Récupérez tous les clients du coach
        $coach_clients = $coachClientRepository->findAll(); // This retrieves all coach clients
    
        // Rendre le template en passant les données à afficher
        return $this->render('coach_client/index.html.twig', [
            'coach_clients' => $coach_clients,
            'coach_admins' => $coach_admins, // Passer les coachs à la vue Twig
            'clicked_count' => $clickedCount,
            'remainingTime' => $remainingTime, // Passer remainingTime au template Twig
        ]);
    }
    private function sendEmail(CoachClient $coachClient, MailerInterface $mailer): void
    {
        $email = (new Email())
            ->from('malekfeki18@gmail.com') // Set your email address here
            ->to( 'inesdkhl@gmail.com') // Send email to the user
            ->subject('Welcome to Our Website')
            ->html('<p> Réclamation  ' );
    
        $mailer->send($email);
    }
    
    #[Route('/new', name: 'app_coach_client_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, CoachAdminRepository $coachAdminRepository, MailerInterface $mailer): Response
    {
        // Vérifier si le nombre d'ajouts a dépassé 3
        $addedCount = count($this->session->get('added_comments', []));
        if ($addedCount >= 3) {
            $blockedUntil = $this->session->get('blocked_until', 0);
            if ($blockedUntil > time()) {
                $remainingTime = max(0, $blockedUntil - time()); // Calcul du temps restant
                return $this->render('coach_client/new.html.twig', [
                    'remainingTime' => $remainingTime,
                ]);
            }
        }
        
        // Récupérer les coachs pour afficher dans le formulaire
        $coach_admins = $coachAdminRepository->findAll();

        $coachClient = new CoachClient();
        $form = $this->createForm(CoachClientType::class, $coachClient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier si le texte contient des mots grossiers
            $commentText = $coachClient->getComment();
            if ($this->containsProfanity($commentText)) {
                return new Response('Votre commentaire contient des mots inappropriés.');
            }

            $entityManager->persist($coachClient);
            $entityManager->flush();
                    // Send welcome email
          $this->sendEmail( $coachClient,$mailer);

            // Enregistrer l'ajout du commentaire dans la session
            $editedComments = $this->session->get('edited_comments', []);
            $editedComments[] = $coachClient->getId();
            $this->session->set('edited_comments', $editedComments);

            // Réinitialiser le compteur et bloquer pendant 20 secondes
            $this->session->set('blocked_until', time() + 20);

            return $this->redirectToRoute('app_coach_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('coach_client/new.html.twig', [
            'coach_client' => $coachClient,
            'form' => $form,
            'coach_admins' => $coach_admins, // Passer les coachs au modèle Twig
            'remainingTime' => 0, // Ajoutez cette ligne pour éviter l'erreur lorsque la condition n'est pas remplie
        ]);
    }

    #[Route('/{id}', name: 'app_coach_client_show', methods: ['GET'])]
    public function show(CoachClient $coachClient): Response
    {
        return $this->render('coach_client/show.html.twig', [
            'coach_client' => $coachClient,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_coach_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CoachClient $coachClient, EntityManagerInterface $entityManager, CoachAdminRepository $coachAdminRepository): Response
    {
        $form = $this->createForm(CoachClientType::class, $coachClient);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier si le commentaire a déjà été modifié plus de 3 fois
            $editedComments = $this->session->get('edited_comments', []);
            if (count($editedComments) < 3) {
                $entityManager->flush();
    
                // Enregistrer le commentaire comme modifié dans la session
                $editedComments[] = $coachClient->getId();
                $this->session->set('edited_comments', $editedComments);
    
                return $this->redirectToRoute('app_coach_client_index', [], Response::HTTP_SEE_OTHER);
            } else {
                return new Response('Vous ne pouvez pas modifier ce commentaire plus de 3 fois.');
            }
        }
    
        // Récupérer les coachs pour afficher dans le formulaire
        $coach_admins = $coachAdminRepository->findAll();
    
        return $this->renderForm('coach_client/edit.html.twig', [
            'coach_client' => $coachClient,
            'form' => $form,
            'coach_admins' => $coach_admins, // Passer les coachs au modèle Twig
        ]);
    }
    
    #[Route('/{id}', name: 'app_coach_client_delete', methods: ['POST'])]
    public function delete(Request $request, CoachClient $coachClient, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$coachClient->getId(), $request->request->get('_token'))) {
            $entityManager->remove($coachClient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_coach_client_index', [], Response::HTTP_SEE_OTHER);
    }

    // Fonction pour détecter les mots grossiers
    private function containsProfanity(string $text): bool
    {
        $profaneWords = ['word1', 'word2', 'word3']; // Liste de mots grossiers à détecter

        foreach ($profaneWords as $profaneWord) {
            if (stripos($text, $profaneWord) !== false) {
                return true;
            }
        }

        return false;
    }
}
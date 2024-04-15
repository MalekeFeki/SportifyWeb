<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Mercure\Update;



#[Route('/user')]
class UserController extends AbstractController
{  // private $publisher;

   // public function __construct(PublisherInterface $publisher)
    //{
    //    $this->publisher = $publisher;
    //}
    #[Route('/frontend', name: 'frontend', methods: ['GET'])]
    public function showFrontendPage(): Response
    {
        return $this->render('base.html.twig');
    }
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, Request $request, PaginatorInterface $paginator): Response
{
    // Get all users from the repository
    $queryBuilder = $userRepository->createQueryBuilder('u');

    // Paginate the results
    $pagination = $paginator->paginate(
        $queryBuilder->getQuery(),
        $request->query->getInt('page', 1), // Get page number from the request, default to 1
        10 // Number of items per page
    );

    // Render the template with paginated users
    return $this->render('user/index.html.twig', [
        'pagination' => $pagination,
    ]);
}
    #[Route('/count', name: 'api_user_count', methods: ['GET'])]
    public function countUsers(UserRepository $userRepository): JsonResponse
    {
        // Récupérer le nombre total d'utilisateurs
        $userCount = $userRepository->count([]);

        // Retourner le nombre d'utilisateurs sous forme de réponse JSON
        return $this->json(['userCount' => $userCount]);
    }
    #[Route('/countbyrole', name: 'api_user_count_by_role', methods: ['GET'])]
    public function countUsersByRole(UserRepository $userRepository): Response
    {
        // Compter le nombre d'utilisateurs pour chaque rôle
        $adminCount = $userRepository->countUsersByRole('ADMIN');
        $proprietaireCount = $userRepository->countUsersByRole('PROPRIETAIRE');
        $membreCount = $userRepository->countUsersByRole('MEMBRE');

        // Retourner les nombres d'utilisateurs pour chaque rôle à la vue
        return $this->render('your_template.html.twig', [
            'adminCount' => $adminCount,
            'proprietaireCount' => $proprietaireCount,
            'membreCount' => $membreCount,
        ]);
    }
    #[Route('/profil', name: 'app_user_profil', methods: ['GET'])]
    public function afficherProfil(): Response
    {
        return $this->render('security/profilUser.html.twig');
    }
    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
// Hasher le mot de passe
$hashedPassword = password_hash($user->getPassword(),PASSWORD_DEFAULT);
$user->setMdp($hashedPassword);
            $entityManager->persist($user);
            $entityManager->flush();

             // Send welcome email
        $this->sendWelcomeEmail($user, $mailer);
        // Émettre une notification Mercure pour informer des nouveaux utilisateurs
        //$update = new Update(
        //    '/notifications/user-added',
        //    json_encode(['message' => 'New user added'])
        //);
        //$this->publisher->__invoke($update);
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
   
    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(UserType::class, $user,['password_required' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hasher le nouveau mot de passe s'il a été modifié
        if ($form->get('mdp')->getData() !== null) {
            $hashedPassword = $passwordEncoder->encodePassword($user, $form->get('mdp')->getData());
            $user->setMdp($hashedPassword);
        }
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
    private function sendWelcomeEmail(User $user, MailerInterface $mailer): void
{
    $email = (new Email())
        ->from('malekfeki18@gmail.com') // Set your email address here
        ->to($user->getEmail()) // Send email to the user
        ->subject('Welcome to Our Website')
        ->html('<p>Hello ' . $user->getNom() . ',<br>Welcome to our website!</p>');

    $mailer->send($email);
}






}
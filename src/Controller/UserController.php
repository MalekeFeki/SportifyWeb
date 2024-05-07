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
use Symfony\Component\Security\Core\Security;
use Twilio\Rest\Client;
use App\Util\RandomPasswordGenerator;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


#[Route('/user')]
class UserController extends AbstractController

{  
    private $passwordGenerator;
    private $entityManager;
    private $mailer;
    private $session;
    
public function __construct(EntityManagerInterface $entityManager, MailerInterface $mailer, RandomPasswordGenerator $passwordGenerator,SessionInterface $session)
    {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
        $this->passwordGenerator = $passwordGenerator;
        $this->session = $session;
    } 
#[Route('/frontend', name: 'frontend', methods: ['GET'])]
public function showFrontendPage(): Response
    {
        return $this->render('base.html.twig');
    }
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
public function index(UserRepository $userRepository, Request $request, PaginatorInterface $paginator): Response
{
    $roleFilter = $request->query->get('role');
    $searchTerm = $request->query->get('search');
    // Get all users from the repository
    $queryBuilder = $userRepository->createQueryBuilder('u');

    // Apply role filter if provided
    if ($roleFilter) {
        $queryBuilder->andWhere('u.role = :role')
                    ->setParameter('role', $roleFilter);
    }
      // Apply search filter if provided
      if ($searchTerm) {
        $queryBuilder->andWhere('u.nom LIKE :searchTerm OR u.prenom LIKE :searchTerm')
                    ->setParameter('searchTerm', '%' . $searchTerm . '%');
    }

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

#[Route('/forgot-password', name: 'forgot_password', methods: ['GET', 'POST'])]
public function forgotPassword(Request $request): Response
{
    // Vérifier si le formulaire a été soumis
    if ($request->isMethod('POST')) {
        // Récupérer l'e-mail saisi dans le formulaire
        $email = $request->request->get('email');

        // Recherchez l'utilisateur par son e-mail
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $email]);

        // Si l'utilisateur n'existe pas, redirigez avec un message d'erreur
        if (!$user) {
            $this->addFlash('error', 'Email not found.');
            return $this->redirectToRoute('forgot_password');
        }

        // Générer un nouveau mot de passe aléatoire
        $newPassword = $this->passwordGenerator->generate();

        // Mettre à jour le mot de passe de l'utilisateur dans la base de données
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $user->setPassword($hashedPassword);
        $this->entityManager->flush();

        // Envoyer le nouveau mot de passe par e-mail
        $this->sendNewPasswordByEmail($user, $newPassword);

        // Rediriger vers une page de confirmation
        return $this->redirectToRoute('password_reset_confirmation');
    }

    // Afficher le formulaire de saisie de l'e-mail
    return $this->render('reset_password/index.html.twig');
}


private function sendNewPasswordByEmail(User $user, string $newPassword): void
{
    $email = (new Email())
        ->from('malekfeki18@gmail.com')
        ->to($user->getEmail())
        ->subject('Votre nouveau mot de passe')
        ->text('La réinitialisation du mot de passe est validé.Votre nouveau mot de passe est: ' . $newPassword);

    $this->mailer->send($email);
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

       // Return role counts as JSON response
    return $this->json([
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
           
             // Send welcome email and sms
        $this->sendWelcomeEmail($user, $mailer);
        $this->sendWelcomeSms($user);
    // Trigger success flash message
    $this->addFlash('success', 'User created successfully.');
        
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
#[Route('/{id}/editprofile', name: 'app_profile_edit', methods: ['GET', 'POST'])]
public function profileEdit(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
{
    // Get the user ID from the submitted form data
    $userId = $request->request->get('user_id');
    
    // Find the user entity by ID
    $user = $userRepository->find($userId);

    if (!$user) {
        throw $this->createNotFoundException('User not found');
    }
     

    // Update user properties with the submitted form data
    $user->setEmail($request->request->get('email'));
    $user->setNom($request->request->get('nom'));
    $user->setPrenom($request->request->get('prenom'));
    $user->setNumTel($request->request->get('num_tel'));
    $user->setCin($request->request->get('cin'));
    $user->setImage($request->request->get('image'));

    // Persist changes to the database
    $entityManager->flush();
    // Pass the user's name to the template
    $username = $user->getNom() . ' ' . $user->getPrenom();
    // Trigger success flash message
    $this->addFlash('success',  $username .' a modifié son profil.');

    // Redirect to some route after successful update
    return $this->render('security/profilUser.html.twig');
}
private function sendWelcomeSms(User $user): void
{
    $a = "";
    $b = "";
    $twilio = new Client($a, $b);
    $message = $twilio->messages
        ->create("+216" .$user->getNumTel(), // to
            array(
                "from" => "+12563611762",
                "body" => "Bonjour " . $user->getUsername() . ", bienvenue dans notre site web Sportify!"
            )
        );


}

}

      
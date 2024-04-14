<?php


namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordController extends AbstractController
{
    /**
     * @Route("/reset-password", name="reset_password")
     */
    public function resetPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder,UserRepository $repo)
    {
        $user = $this->getUser(); // Récupère l'utilisateur actuellement connecté

        // Vérifie si l'utilisateur est connecté
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour réinitialiser votre mot de passe.');
        }

        // Récupère les données du formulaire
        $newPassword = $request->request->get('password');

        // Valider les données du formulaire si nécessaire

        // Crypter le nouveau mot de passe
        $encodedPassword = $passwordEncoder->encodePassword($user, $newPassword);

        // Mettre à jour le mot de passe de l'utilisateur
       // $user->setMdp($encodedPassword);

        // Sauvegarder les changements dans la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        // Rediriger l'utilisateur ou afficher un message de succès
        return $this->redirectToRoute('dashboard');
    }
}
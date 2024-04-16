<?php

namespace App\Controller;

use App\Entity\CoachAdmin; // Import the CoachAdmin entity
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        // Example of retrieving data from the database using CoachAdmin entity
        $coachAdmins = $this->entityManager->getRepository(CoachAdmin::class)->findAll();

        return $this->render('base.html.twig', [
            'controller_name' => 'HomeController',
            'coach_admins' => $coachAdmins,
        ]);
    }
}

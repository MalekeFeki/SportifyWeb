<?php

namespace App\Controller;

use App\Entity\Challenge;
use App\Form\ChallengeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/challenge')]
class ChallengeController extends AbstractController
{
    #[Route('/', name: 'app_challenge_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $challenges = $entityManager
            ->getRepository(Challenge::class)
            ->findAll();

        return $this->render('challenge/index.html.twig', [
            'challenges' => $challenges,
        ]);
    }

    #[Route('/new', name: 'app_challenge_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $challenge = new Challenge();
        $form = $this->createForm(ChallengeType::class, $challenge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Check if the description is empty
            $description = $challenge->getDescription();
            if (empty($description)) {
                return new Response('<script>alert("Description cannot be empty"); window.history.back();</script>');
            }

            // Check if the description contains the word "chat"
            if (stripos($description, 'chat') !== false) {
                return new Response('<script>alert("This description contains bad words"); window.history.back();</script>');
            }

            $entityManager->persist($challenge);
            $entityManager->flush();

            return $this->redirectToRoute('app_challenge_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('challenge/new.html.twig', [
            'challenge' => $challenge,
            'form' => $form,
        ]);
    }

    #[Route('/{idc}', name: 'app_challenge_show', methods: ['GET'])]
    public function show(Challenge $challenge): Response
    {
        return $this->render('challenge/show.html.twig', [
            'challenge' => $challenge,
        ]);
    }

    #[Route('/{idc}/edit', name: 'app_challenge_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Challenge $challenge, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ChallengeType::class, $challenge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_challenge_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('challenge/edit.html.twig', [
            'challenge' => $challenge,
            'form' => $form,
        ]);
    }

    #[Route('/{idc}', name: 'app_challenge_delete', methods: ['POST'])]
    public function delete(Request $request, Challenge $challenge, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$challenge->getIdc(), $request->request->get('_token'))) {
            $entityManager->remove($challenge);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_challenge_index', [], Response::HTTP_SEE_OTHER);
    }
}

<?php

namespace App\Controller;

use App\Entity\Salle;
use App\Entity\Seance;
use App\Form\SeanceType;
use App\Form\SalleType;
use App\Repository\SalleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;


#[Route('/salle')]
class SalleController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(SalleRepository $salleRepository): Response
    {
        
        return $this->render('salle/index.html.twig', [
            'salles' => $salleRepository -> findAll(),
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
            $file = $form['imagesalle']->getData();
            if ($file) {
                $fileName = md5(uniqid()).'.'.$file->guessExtension();

                // Move the file to the directory where you want to store it
                $file->move(
                    $this->getParameter('salle_images_directory'),
                    $fileName
                );

                // Set the file name to the Salle entity
                $salle->setImagesalle($fileName);
            }

            $entityManager->persist($salle);
            $entityManager->flush();

            return $this->redirectToRoute('index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('salle/new.html.twig', [
            'salle' => $salle,
            'form' => $form,
        ]);
    }


    #[Route('/{idS}', name: 'app_salle_show', methods: ['GET'])]
    public function show(Salle $salle): Response
    {
        return $this->render('salle/show.html.twig', [
            'salle' => $salle,
        ]);
    }

    #[Route('/{idS}/edit', name: 'app_salle_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Salle $salle, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(SalleType::class, $salle);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Handle file upload
        $file = $form['imagesalle']->getData();
        if ($file instanceof UploadedFile) {
            $fileName = uniqid().'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('salle_images_directory'),
                $fileName
            );
            $salle->setImagesalle($fileName);
        }

        $entityManager->flush();

        return $this->redirectToRoute('index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('salle/edit.html.twig', [
        'salle' => $salle,
        'form' => $form,
    ]);
}

    #[Route('/{idS}', name: 'app_salle_delete', methods: ['POST'])]
    public function delete(Request $request, Salle $salle, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$salle->getIds(), $request->request->get('_token'))) {
            $entityManager->remove($salle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('index', [], Response::HTTP_SEE_OTHER);
    }

    // #[Route('/newseance/{idS}', name: '')]
public function newseance(Salle $salle): Response
{
    return $this->redirectToRoute('app_seance_new', ['idS' => $salle->getIdS()]);
}










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

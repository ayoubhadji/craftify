<?php

namespace App\Controller;

use App\Entity\Aventurier;
use App\Form\AventurierType;
use App\Repository\AventurierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/aventurier')]
final class AventurierController extends AbstractController
{
    #[Route(name: 'app_aventurier_index', methods: ['GET'])]
    public function index(AventurierRepository $aventurierRepository): Response
    {
        return $this->render('aventurier/index.html.twig', [
            'aventuriers' => $aventurierRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_aventurier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $aventurier = new Aventurier();
        $form = $this->createForm(AventurierType::class, $aventurier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($aventurier);
            $entityManager->flush();

            // Add a success message to the session
            $this->addFlash('success', 'L\'aventurier a été créé avec succès !');

            // Redirect to the index page after successful creation
            return $this->redirectToRoute('app_aventurier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('aventurier/new.html.twig', [
            'aventurier' => $aventurier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_aventurier_show', methods: ['GET'])]
    public function show(Aventurier $aventurier): Response
    {
        return $this->render('aventurier/show.html.twig', [
            'aventurier' => $aventurier,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_aventurier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Aventurier $aventurier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AventurierType::class, $aventurier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_aventurier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('aventurier/edit.html.twig', [
            'aventurier' => $aventurier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_aventurier_delete', methods: ['POST'])]
    public function delete(Request $request, Aventurier $aventurier, EntityManagerInterface $entityManager): Response
    {
        // Vérification du token CSRF
        if ($this->isCsrfTokenValid('delete' . $aventurier->getId(), $request->request->get('_token'))) {
            // Suppression de l'entité et enregistrement des changements
            $entityManager->remove($aventurier);
            $entityManager->flush();

            // Message de confirmation
            $this->addFlash('success', 'L\'aventurier a été supprimé avec succès.');
        } else {
            // Message d'erreur si le token n'est pas valide
            $this->addFlash('error', 'Erreur de sécurité. Suppression échouée.');
        }

        // Redirection vers la liste des aventuriers après la suppression
        return $this->redirectToRoute('app_aventurier_index', [], Response::HTTP_SEE_OTHER);
    }
}

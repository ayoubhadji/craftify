<?php

namespace App\Controller;

use App\Entity\Expedition;
use App\Form\ExpeditionType;
use App\Repository\ExpeditionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;



final class ExpeditionController extends AbstractController
{
    #[Route('/expedition',name: 'app_expedition_index', methods: ['GET'])]
    public function index(ExpeditionRepository $expeditionRepository): Response
    {
        return $this->render('expedition/index.html.twig', [
            'expeditions' => $expeditionRepository->findAll(),
        ]);
    }

    #[Route('/expedition/new', name: 'app_expedition_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $expedition = new Expedition();
        $form = $this->createForm(ExpeditionType::class, $expedition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($expedition);
            $entityManager->flush();

            $this->addFlash('success', 'Expédition créée avec succès.');
            return $this->redirectToRoute('app_expedition_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('expedition/new.html.twig', [
            'expedition' => $expedition,
            'form' => $form,
        ]);
    }

    #[Route('/expedition/{id}', name: 'app_expedition_show', methods: ['GET'])]
    public function show(Expedition $expedition): Response
    {
        return $this->render('expedition/show.html.twig', [
            'expedition' => $expedition,
        ]);
    }

    #[Route('/expedition/{id}/edit', name: 'app_expedition_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Expedition $expedition, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ExpeditionType::class, $expedition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Expédition mise à jour avec succès.');
            return $this->redirectToRoute('app_expedition_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('expedition/edit.html.twig', [
            'expedition' => $expedition,
            'form' => $form,
        ]);
    }

    #[Route('/expedition/{id}', name: 'app_expedition_delete', methods: ['POST'])]
    public function delete(Request $request, Expedition $expedition, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $expedition->getId(), $request->request->get('_token'))) {
            $entityManager->remove($expedition);
            $entityManager->flush();

            $this->addFlash('success', 'Expédition supprimée avec succès.');
        } else {
            $this->addFlash('error', 'Erreur lors de la suppression de l\'expédition.');
        }

        return $this->redirectToRoute('app_expedition_index', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/index1', name: 'app_expedition_index1', methods: ['GET'])]
public function front(ExpeditionRepository $expeditionRepository): Response
{
    $expeditions = $expeditionRepository->findAll();
    return $this->render('expedition/index1.html.twig', [
        'expeditions' => $expeditions,
    ]);
}


}
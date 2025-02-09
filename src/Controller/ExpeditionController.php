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

#[Route('/expedition')]
final class ExpeditionController extends AbstractController
{
    #[Route(name: 'app_expedition_index', methods: ['GET'])]
    public function index(ExpeditionRepository $expeditionRepository): Response
    {
        return $this->render('expedition/index.html.twig', [
            'expeditions' => $expeditionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_expedition_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $expedition = new Expedition();
        $form = $this->createForm(ExpeditionType::class, $expedition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($expedition);
            $entityManager->flush();

            return $this->redirectToRoute('app_expedition_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('expedition/new.html.twig', [
            'expedition' => $expedition,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_expedition_show', methods: ['GET'])]
    public function show(Expedition $expedition): Response
    {
        return $this->render('expedition/show.html.twig', [
            'expedition' => $expedition,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_expedition_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Expedition $expedition, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ExpeditionType::class, $expedition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_expedition_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('expedition/edit.html.twig', [
            'expedition' => $expedition,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_expedition_delete', methods: ['POST'])]
    public function delete(Request $request, Expedition $expedition, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$expedition->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($expedition);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_expedition_index', [], Response::HTTP_SEE_OTHER);
    }
}

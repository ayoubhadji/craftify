<?php

namespace App\Controller;

use App\Entity\Foire;
use App\Form\FoireType;
use App\Repository\FoireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/foire')]
final class FoireController extends AbstractController
{
    #[Route(name: 'app_foire_index', methods: ['GET'])]
    public function index(FoireRepository $foireRepository): Response
    {
        return $this->render('foire/index.html.twig', [
            'foires' => $foireRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_foire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $foire = new Foire();
        $form = $this->createForm(FoireType::class, $foire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($foire);
            $entityManager->flush();

            return $this->redirectToRoute('app_foire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('foire/new.html.twig', [
            'foire' => $foire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_foire_show', methods: ['GET'])]
    public function show(Foire $foire): Response
    {
        return $this->render('foire/show.html.twig', [
            'foire' => $foire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_foire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Foire $foire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FoireType::class, $foire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_foire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('foire/edit.html.twig', [
            'foire' => $foire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_foire_delete', methods: ['POST'])]
    public function delete(Request $request, Foire $foire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$foire->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($foire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_foire_index', [], Response::HTTP_SEE_OTHER);
    }
}

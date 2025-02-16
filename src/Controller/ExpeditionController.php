<?php

namespace App\Controller;

use Faker\Factory;
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

            $this->addFlash('success', 'Expédition créée avec succès.');
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

            $this->addFlash('success', 'Expédition mise à jour avec succès.');
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
        if ($this->isCsrfTokenValid('delete' . $expedition->getId(), $request->request->get('_token'))) {
            $entityManager->remove($expedition);
            $entityManager->flush();

            $this->addFlash('success', 'Expédition supprimée avec succès.');
        } else {
            $this->addFlash('error', 'Erreur lors de la suppression de l\'expédition.');
        }

        return $this->redirectToRoute('app_expedition_index', [], Response::HTTP_SEE_OTHER);
    }

    // 🚀 Génération automatique d'expéditions avec Faker
    #[Route('/generate', name: 'app_expedition_generate', methods: ['GET'])]
    public function generate(EntityManagerInterface $entityManager): Response
    {
        $faker = Factory::create('fr_FR'); // Faker en français

        $expedition = new Expedition();
        $expedition->setNomExpedition($faker->sentence(3));
        $expedition->setUnivers($faker->randomElement(['Steampunk', 'Méditerranée Mythique', 'Afrique Ancienne']));
        $expedition->setCarteTresorUrl($faker->imageUrl());
        $expedition->setQuetesDisponibles($faker->paragraph(2));
        $expedition->setObjetsMagiques($faker->word());
        $expedition->setGardiensArtisanaux($faker->name());
        $expedition->setDureeMystique($faker->randomElement(['Un cycle lunaire', 'Un rituel secret']));
        $expedition->setSecretsCaches($faker->sentence());
        $expedition->setReliqueFinale($faker->sentence());

        $entityManager->persist($expedition);
        $entityManager->flush();

        $this->addFlash('success', 'Expédition générée avec succès !');

        return $this->redirectToRoute('app_expedition_index');
    }
}
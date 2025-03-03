<?php

namespace App\Controller;

use App\Entity\Expedition;
use App\Form\ExpeditionType;
use App\Entity\AventurierExpedition;
use App\Entity\Quiz;
use App\Form\AventurierExpeditionType;
use App\Repository\AventurierExpeditionRepository;
use App\Repository\ExpeditionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;


final class ExpeditionController extends AbstractController
{ 
    
    #[Route('/expedition', name: 'app_expedition_index', methods: ['GET'])]
    public function index(ExpeditionRepository $expeditionRepository, Request $request): Response
    {
        // Récupérer les critères de filtrage depuis la requête
        $titre = $request->query->get('titre');
        $aventurierId = $request->query->get('aventurier');
        $objectif = $request->query->get('objectif');
    
        // Créer une requête pour filtrer les expéditions
        $expeditions = $expeditionRepository->findByFilters($titre, $aventurierId, $objectif);
    
        return $this->render('expedition/index.html.twig', [
            'expeditions' => $expeditions,
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

    #[Route('/show1/{id}', name: 'app_expedition_show1', methods: ['GET'])]
    public function show1(ExpeditionRepository $expeditionRepository, int $id): Response
    {
        $expedition = $expeditionRepository->find($id);

        if (!$expedition) {
            throw $this->createNotFoundException('Expédition non trouvée.');
        }

        return $this->render('expedition/show1.html.twig', [
            'expedition' => $expedition,
        ]);
    }
    private HttpClientInterface $client;
    private string $youtubeApiKey;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
        $this->youtubeApiKey = 'AIzaSyAyXf7LgtQl3_Ekwj1PF-eANouAsKwi3Z0'; 
    }

    #[Route('/expedition/{id}/video', name: 'app_expedition_vd')]
    public function vd(Expedition $expedition): Response
    {
        // Vérification et formatage de l'URL YouTube
        $videoUrl = $this->formatYoutubeUrl($expedition->getVideoUrl());

        return $this->render('expedition/video.html.twig', [
            'expedition' => $expedition,
            'videoUrl' => $videoUrl, // Envoi de l'URL formatée au template
        ]);
    }

    /**
     * Vérifie si la vidéo est intégrable et retourne l'URL embed si possible.
     */
    private function formatYoutubeUrl(?string $url): ?string
    {
        if (!$url) {
            return null; // Pas d'URL fournie
        }

        // Extraction de l'ID de la vidéo YouTube
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/[^\/]+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $url, $matches);

        if (!isset($matches[1])) {
            return null; // URL invalide
        }

        $videoId = $matches[1];

        // Vérification si la vidéo est intégrable via l'API YouTube
        $response = $this->client->request('GET', 'https://www.googleapis.com/youtube/v3/videos', [
            'query' => [
                'id' => $videoId,
                'key' => $this->youtubeApiKey,
                'part' => 'status'
            ]
        ]);

        $data = $response->toArray();

        if (!isset($data['items'][0]['status']['embeddable']) || !$data['items'][0]['status']['embeddable']) {
            return null; // La vidéo ne peut pas être intégrée
        }

        return "https://www.youtube.com/embed/" . $videoId;
    }
   

   
}

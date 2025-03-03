<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class EvenementController extends AbstractController
{
    #[Route('/evenement', name: 'app_evenement_index', methods: ['GET'])]
    public function index(EvenementRepository $evenementRepository, Request $request): Response
    {
        // Get the current page from the query string (default to 1)
        $page = $request->query->getInt('page', 1);
        $limit = 3; // Number of events per page

        // Retrieve sorting parameters
        $sortBy = $request->query->get('sortBy', 'nom'); // Default sort by 'nom'
        $order = $request->query->get('order', 'asc'); // Default order is ascending

        // Retrieve a paginated list of events with sorting
        $queryBuilder = $evenementRepository->createQueryBuilder('e');

        // Apply sorting
        $queryBuilder->orderBy('e.' . $sortBy, $order);

        // Apply pagination
        $totalEvenements = count($evenementRepository->findAll());
        $totalPages = ceil($totalEvenements / $limit);

        $evenements = $queryBuilder
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        return $this->render('evenement/index.html.twig', [
            'evenements'  => $evenements,
            'sortBy'      => $sortBy,
            'order'       => $order,
            'currentPage' => $page,
            'totalPages'  => $totalPages,
        ]);
    }


    #[Route('/evenement/new',name: 'app_evenement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evenement_show', methods: ['GET'])]
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    #[Route('/evenement/{id}/edit', name: 'app_evenement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/evenement/{id}', name: 'app_evenement_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($evenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
    }

    //front display event cards
    #[Route('/evenement/frontivo',name: 'app_evenement_front', methods: ['GET'])]
    public function front(EvenementRepository $evenementRepository): Response
    {
        return $this->render('evenement/front.html.twig', [
            'evenements' => $evenementRepository->findAll(),
        ]);
    }
    

    //front display event cards in details
    #[Route('/evenement/front/{id}', name: 'app_evenement_front_show', methods: ['GET'])]
    public function showfront(Evenement $evenement): Response
    {
        return $this->render('evenement/front_show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    

  
// New search action using a dedicated search twig template.
#[Route('/evenement/search', name: 'app_evenement_search', methods: ['GET'])]
public function ssearch(Request $request, EvenementRepository $evenementRepository): Response
{
    // Retrieve the search term from the query parameters.
    $searchTerm = $request->query->get('search');

    if ($searchTerm) {
        // Use a custom repository method to search by place.
        $evenements = $evenementRepository->findByPlace($searchTerm);
    } else {
        $evenements = $evenementRepository->findAll();
    }

    // Render the dedicated search results template.
    return $this->render('evenement/search.html.twig', [
        'evenements' => $evenements,
    ]);
}




}
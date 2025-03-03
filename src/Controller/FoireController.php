<?php

namespace App\Controller;

use App\Entity\Foire;
use App\Form\FoireType;
use Symfony\Component\HttpFoundation\RedirectResponse; // Correct import for RedirectResponse
use App\Repository\FoireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SliderItemRepository;



#[Route('/foire')]
final class FoireController extends AbstractController
{
    

    
    #[Route(name: 'app_foire_index', methods: ['GET'])]
    public function index(SliderItemRepository $SliderItemRepository): Response
    {
        
        return $this->render('foire/dex.html.twig', [
            'slider_items' => $SliderItemRepository->findAll(),
        ]);
    }

    #[Route('/gg', name: 'foir_front', methods: ['GET'])]
    public function inno(FoireRepository $foireRepository, Request $request): Response
    {
        // Pagination settings
        $page = $request->query->getInt('page', 1); // Default to page 1
        $limit = 1; // Limit the number of Foires per page
    
        // Search functionality
        $searchTerm = $request->query->get('search', '');
        
        // Get total number of Foires for pagination
        $totalFoires = count($foireRepository->findAll());
        $totalPages = ceil($totalFoires / $limit);
    
        // Fetch Foires with search and pagination
        $queryBuilder = $foireRepository->createQueryBuilder('f')
            ->where('f.nom LIKE :search')
            ->setParameter('search', '%' . $searchTerm . '%')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);
        
        $foires = $queryBuilder->getQuery()->getResult();
    
        return $this->render('foire/index.html.twig', [
            'foires' => $foires,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $searchTerm,
        ]);
    }
    
    #[Route('/xx', name: 'big_foir', methods: ['GET'])]
    public function giga(FoireRepository $foireRepository, Request $request): Response
    {
        // Pagination settings
        $page = $request->query->getInt('page', 1); // Default to page 1
        $limit = 1; // Limit the number of Foires per page
    
        // Search functionality
        $searchTerm = $request->query->get('search', '');
        
        // Get total number of Foires for pagination
        $totalFoires = count($foireRepository->findAll());
        $totalPages = ceil($totalFoires / $limit);
    
        // Fetch Foires with search and pagination
        $queryBuilder = $foireRepository->createQueryBuilder('f')
            ->where('f.nom LIKE :search')
            ->setParameter('search', '%' . $searchTerm . '%')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);
        
        $foires = $queryBuilder->getQuery()->getResult();
    
        return $this->render('foire/dexx.html.twig', [
            'foires' => $foires,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $searchTerm,
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

            return $this->redirectToRoute('foir_front', [], Response::HTTP_SEE_OTHER);
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

            return $this->redirectToRoute('foir_front', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('foire/edit.html.twig', [
            'foire' => $foire,
            'form' => $form,
        ]);
    }

    #[Route('/foire/delete/{id}', name: 'app_foire_delete')]
public function delete(int $id, EntityManagerInterface $entityManager, SliderItemRepository $sliderItemRepository): RedirectResponse
{
    // Retrieve the Foire and related SliderItems
    $foire = $entityManager->getRepository(Foire::class)->find($id);

    if (!$foire) {
        throw $this->createNotFoundException('Foire not found.');
    }

    // Delete the related SliderItems
    $sliderItems = $sliderItemRepository->findBy(['foire' => $foire]);
    foreach ($sliderItems as $sliderItem) {
        $entityManager->remove($sliderItem);
    }

    // Delete the Foire
    $entityManager->remove($foire);
    $entityManager->flush();

    return $this->redirectToRoute('foir_front'); // Redirect to the list of Foires
}

    // New function to display all Foires in a list (index page)
    #[Route('/foires', name: 'foire_list', methods: ['GET'])]
    public function listFoires(FoireRepository $foireRepository): Response
    {
        return $this->render('foire/list.html.twig', [
            'foires' => $foireRepository->findAll(), // List all foire entities
        ]);
    }

    // New function to show a specific Foire and its related SliderItems
    #[Route('/{id}/slider-items', name: 'foire_slider_items', methods: ['GET'])]
    public function showSliderItems(Foire $foire): Response
    {
        return $this->render('foire/slider_items.html.twig', [
            'foire' => $foire,  // Pass the specific Foire
            'sliderItems' => $foire->getSliderItems(),  // Assuming SliderItems are related to Foire
        ]);
    }


}

<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/produit')]
final class ProduitController extends AbstractController
{
    // Afficher tous les produits avec pagination, recherche et tri
    #[Route('/all', name: 'app_produit_index', methods: ['GET'])]
    public function allProducts(ProduitRepository $produitRepository, Request $request): Response
    {
        // Nombre de produits par page
        $page = $request->query->getInt('page', 1); // Page courante (par défaut la page 1)
        $limit = 3; // 3 produits par page

        // Récupérer les paramètres de recherche et de tri
        $searchTerm = $request->query->get('search', ''); // Rechercher un terme (par défaut vide)
        $sortBy = $request->query->get('sortBy', 'nom'); // Tri par défaut sur "nom"
        $order = $request->query->get('order', 'asc'); // Tri par défaut en ordre croissant

        // Appliquer les filtres de recherche
        $criteria = [];
        if ($searchTerm) {
            $criteria = [
                'nom' => $searchTerm,
                'description' => $searchTerm,
                'prix' => $searchTerm,
                'stock' => $searchTerm,
            ];
        }

        // Récupérer les produits avec le tri et la recherche
        $queryBuilder = $produitRepository->createQueryBuilder('p');

        // Si un terme de recherche est fourni, appliquer les conditions
        if ($searchTerm) {
            $orX = $queryBuilder->expr()->orX();

            foreach ($criteria as $field => $value) {
                $orX->add("p.$field LIKE :searchTerm");
            }

            $queryBuilder->where($orX)
                        ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        // Appliquer le tri
        $queryBuilder->orderBy('p.' . $sortBy, $order);

        // Pagination
        $totalProduits = count($produitRepository->findAll()); // Total des produits
        $totalPages = ceil($totalProduits / $limit);

        $produits = $queryBuilder
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
            'searchTerm' => $searchTerm,
            'sortBy' => $sortBy,
            'order' => $order,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }

    // Afficher uniquement les produits de l'artisan connecté avec pagination, recherche et tri
    #[Route('/my', name: 'app_produit_my', methods: ['GET'])]
    public function myProducts(ProduitRepository $produitRepository, Request $request): Response
    {
        $user = $request->getSession()->get('user');

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($user->getRole() !== 'ARTISAN') {
            return $this->redirectToRoute('app_produit_all');
        }

        // Nombre de produits par page
        $page = $request->query->getInt('page', 1); // Page courante (par défaut la page 1)
        $limit = 3; // 3 produits par page

        // Récupérer les paramètres de recherche et de tri
        $searchTerm = $request->query->get('search', ''); // Rechercher un terme (par défaut vide)
        $sortBy = $request->query->get('sortBy', 'nom'); // Tri par défaut sur "nom"
        $order = $request->query->get('order', 'asc'); // Tri par défaut en ordre croissant

        // Appliquer les filtres de recherche
        $criteria = [];
        if ($searchTerm) {
            $criteria = [
                'nom' => $searchTerm,
                'description' => $searchTerm,
                'prix' => $searchTerm,
                'stock' => $searchTerm,
            ];
        }

        // Récupérer les produits avec le tri et la recherche
        $queryBuilder = $produitRepository->createQueryBuilder('p')
            ->where('p.artisan = :artisan')
            ->setParameter('artisan', $user);

        // Si un terme de recherche est fourni, appliquer les conditions
        if ($searchTerm) {
            $orX = $queryBuilder->expr()->orX();

            foreach ($criteria as $field => $value) {
                $orX->add("p.$field LIKE :searchTerm");
            }

            $queryBuilder->andWhere($orX)
                        ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        // Appliquer le tri
        $queryBuilder->orderBy('p.' . $sortBy, $order);

        // Pagination
        $totalProduits = count($produitRepository->findBy(['artisan' => $user])); // Total des produits de l'artisan
        $totalPages = ceil($totalProduits / $limit);

        $produits = $queryBuilder
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        return $this->render('produit/myprod.html.twig', [
            'produits' => $produits,
            'searchTerm' => $searchTerm,
            'sortBy' => $sortBy,
            'order' => $order,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }

    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit = new Produit();
        $user = $request->getSession()->get('user');
        
        if (!$user) {
            return $this->redirectToRoute('app_login'); // Redirige vers la page de connexion
        }

        if ($user && $user->getRole() !== 'ARTISAN') {

            $produit->setArtisan($user); // Associer l'utilisateur connecté en tant qu'artisan
        }



        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/proch', name: 'produit_back', methods: ['GET'])]
    public function prosh(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/indexback.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Flush the changes to the database
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            // Handle the file removal if an image exists
            $imgUrl = $produit->getImgUrl();

            if ($imgUrl) {
                $imagePath = $this->getParameter('kernel.project_dir') . '/public/uploads/images/' . $imgUrl;

                if (file_exists($imagePath) && is_file($imagePath)) {
                    unlink($imagePath); // Delete the image file
                }
            }

            // Delete the product
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/proch/{id}', name: 'produit_show_back', methods: ['GET'])]
    public function showback(Produit $produit): Response
    {
        return $this->render('produit/showback.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/proch/{id}/edit', name: 'produit_edit_back', methods: ['GET', 'POST'])]
    public function editback(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('produit_back', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/editback.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }
}
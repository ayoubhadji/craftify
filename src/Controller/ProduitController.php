<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/produit')]
final class ProduitController extends AbstractController
{
    // Afficher tous les produits
    #[Route('/all', name: 'app_produit_index', methods: ['GET'])]
    public function allProducts(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    // Afficher uniquement les produits de l'artisan connecté
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

        $produits = $produitRepository->findBy(['id_artisan' => $user]);

        return $this->render('produit/myprod.html.twig', [
            'produits' => $produits,
        ]);
    }
    


    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit = new Produit();
        $user = $request->getSession()->get('user');


        if ($user && in_array('ROLE_ARTISAN', [$user->getRole()])) {

            $produit->setIdArtisan($user); // Associer l'utilisateur connecté en tant qu'artisan
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
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_produit_delete', methods: ['POST'])]
public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
{
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
        
        // Récupérer l'image associée
        $imgUrl = $produit->getImgUrl();

        if (!empty($imgUrl) && is_string($imgUrl)) {
            $imagePath = $this->getParameter('kernel.project_dir') . '/public/uploads/images/' . $imgUrl;

            // Vérifier que le chemin est valide, que le fichier existe et qu'il est bien un fichier
            if (file_exists($imagePath) && is_file($imagePath) && strpos($imagePath, "\0") === false) {
                unlink($imagePath); // Supprime l'image
            }
        }

        // Supprimer le produit de la base de données
        $entityManager->remove($produit);
        $entityManager->flush();
    }

    return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
}

}

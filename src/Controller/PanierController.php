<?php

namespace App\Controller;

use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierController extends AbstractController
{
    #[Route('/panier/ajouter/{id}', name: 'app_panier_ajouter', methods: ['POST'])]
    public function ajouterAuPanier(Produit $produit, SessionInterface $session, Request $request): Response
    {
        // Vérification du token CSRF
        if (!$this->isCsrfTokenValid('ajouter_panier_' . $produit->getId(), $request->request->get('_token'))) {
            $this->addFlash('danger', 'Action non autorisée.');
            return $this->redirectToRoute('app_produit_index');
        }

        // Récupérer le panier de la session
        $panier = $session->get('panier', []);

        // Ajouter le produit au panier ou incrémenter la quantité
        $id = $produit->getId();
        if (!isset($panier[$id])) {
            $panier[$id] = [
                'id' => $produit->getId(),
                'nom' => $produit->getNom(),
                'prix' => $produit->getPrix(),
                'quantite' => 1
            ];
        } else {
            $panier[$id]['quantite']++;
        }

        // Mettre à jour la session
        $session->set('panier', $panier);

        // Message de confirmation
        $this->addFlash('success', 'Produit ajouté au panier !');

        // Redirection vers la page des produits
        return $this->redirectToRoute('app_produit_index');
    }

    #[Route('/panier', name: 'app_panier')]
    public function afficherPanier(SessionInterface $session): Response
    {
        $panier = $session->get('panier', []);

        return $this->render('panier/index.html.twig', [
            'panier' => $panier
        ]);
    }

    #[Route('/panier/vider', name: 'app_panier_vider')]
    public function viderPanier(SessionInterface $session): Response
    {
        $session->remove('panier');
        $this->addFlash('success', 'Panier vidé avec succès.');
        return $this->redirectToRoute('app_produit_index');
    }

    #[Route('/commande/valider', name: 'app_commande_valider')]
    public function validerCommande(SessionInterface $session): Response
    {
        $panier = $session->get('panier', []);

        if (empty($panier)) {
            $this->addFlash('danger', 'Votre panier est vide.');
            return $this->redirectToRoute('app_panier');
        }

        // Sauvegarder la commande dans la base de données
        // Exemple: ajouter une entité Commande et lier les produits avec une entité CommandeProduit

        // Vider le panier après la commande
        $session->remove('panier');

        $this->addFlash('success', 'Votre commande a bien été enregistrée !');

        return $this->redirectToRoute('app_produit_index');
    }
}
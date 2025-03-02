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
        if (!$this->isCsrfTokenValid('ajouter_panier_' . $produit->getId(), $request->request->get('_token'))) {
            $this->addFlash('danger', 'Action non autorisée.');
            return $this->redirectToRoute('app_produit_index');
        }

        $panier = $session->get('panier', []);

        $id = $produit->getId();
        if (!isset($panier[$id])) {
            $panier[$id] = [
                'id' => $produit->getId(),
                'nom' => $produit->getNom(),
                'prix' => $produit->getPrix(),
                'quantite' => 1
            ];
            $this->addFlash('success', sprintf('Le produit "%s" a été ajouté au panier !', $produit->getNom()));
        } else {
            $panier[$id]['quantite']++;
            $this->addFlash('success', sprintf('Quantité du produit "%s" augmentée.', $produit->getNom()));
        }

        $session->set('panier', $panier);
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
        $this->addFlash('success', 'Le panier a été vidé.');
        return $this->redirectToRoute('app_produit_index');
    }

    #[Route('/panier/supprimer/{id}', name: 'app_panier_supprimer', methods: ['POST'])]
    public function supprimerDuPanier(int $id, SessionInterface $session, Request $request): Response
    {
        if (!$this->isCsrfTokenValid('supprimer_panier_' . $id, $request->request->get('_token'))) {
            $this->addFlash('danger', 'Action non autorisée.');
            return $this->redirectToRoute('app_panier');
        }

        $panier = $session->get('panier', []);

        if (isset($panier[$id])) {
            $nomProduit = $panier[$id]['nom'];
            unset($panier[$id]);
            $session->set('panier', $panier);
            $this->addFlash('success', sprintf('Le produit "%s" a été supprimé.', $nomProduit));
        } else {
            $this->addFlash('danger', 'Produit introuvable.');
        }

        return $this->redirectToRoute('app_panier');
    }

    #[Route('/panier/modifier/{id}', name: 'app_panier_modifier_quantite', methods: ['POST'])]
    public function modifierQuantite(int $id, SessionInterface $session, Request $request): Response
    {
        if (!$this->isCsrfTokenValid('modifier_quantite_' . $id, $request->request->get('_token'))) {
            $this->addFlash('danger', 'Action non autorisée.');
            return $this->redirectToRoute('app_panier');
        }

        $panier = $session->get('panier', []);

        if (isset($panier[$id])) {
            $action = $request->request->get('action');

            if ($action === 'incrementer') {
                $panier[$id]['quantite']++;
            } elseif ($action === 'decrementer' && $panier[$id]['quantite'] > 1) {
                $panier[$id]['quantite']--;
            }

            $session->set('panier', $panier);
        }

        return $this->redirectToRoute('app_panier');
    }

    #[Route('/commande/valider', name: 'app_commande_valider')]
    public function validerCommande(SessionInterface $session): Response
    {
        $panier = $session->get('panier', []);

        if (empty($panier)) {
            $this->addFlash('danger', 'Votre panier est vide.');
            return $this->redirectToRoute('app_panier');
        }

        // Sauvegarde de la commande en BDD (à implémenter)

        $session->remove('panier');
        $this->addFlash('success', 'Votre commande a été enregistrée !');

        return $this->redirectToRoute('app_produit_index');
    }
}

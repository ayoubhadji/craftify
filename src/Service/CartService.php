<?php

namespace App\Service;

use App\Entity\Panier;
use App\Entity\User;
use App\Entity\Produit;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CartService
{
    private PanierRepository $panierRepository;
    private ProduitRepository $produitRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        PanierRepository $panierRepository,
        ProduitRepository $produitRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->panierRepository = $panierRepository;
        $this->produitRepository = $produitRepository;
        $this->entityManager = $entityManager;
    }

    public function add(int $produitId, User $user): void
    {
        $produit = $this->produitRepository->find($produitId);
        if (!$produit || $produit->getStock() <= 0) {
            throw new \LogicException("Le produit n'existe pas ou est en rupture de stock.");
        }

        $panier = $this->panierRepository->findOneBy([
            'user'    => $user,
            'produit' => $produit,
        ]);

        $this->entityManager->beginTransaction();

        try {
            if ($panier) {
                if ($panier->getQuantity() < $produit->getStock()) {
                    $panier->setQuantity($panier->getQuantity() + 1);
                } else {
                    throw new \LogicException("Stock insuffisant pour ajouter plus d'unités.");
                }
            } else {
                $panier = new Panier();
                $panier->setUser($user);
                $panier->setProduit($produit);
                $panier->setQuantity(1);
                $this->entityManager->persist($panier);
            }

            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }

    public function remove(int $produitId, User $user): void
    {
        $produit = $this->produitRepository->find($produitId);
        if (!$produit) {
            throw new \LogicException("Produit introuvable !");
        }

        $panier = $this->panierRepository->findOneBy([
            'user'    => $user,
            'produit' => $produit,
        ]);

        if ($panier) {
            $this->entityManager->remove($panier);
            $this->entityManager->flush();
        }
    }

    /**
     * Récupère les articles du panier de l'utilisateur.
     * @return Panier[]
     */
    public function getCart(User $user): array
    {
        return $this->panierRepository->findBy(['user' => $user]);
    }

    /**
     * Calcule le total du panier.
     */
    public function getTotal(User $user): float
    {
        return array_reduce($this->getCart($user), function ($total, Panier $item) {
            return $total + ($item->getProduit()->getPrix() * $item->getQuantity());
        }, 0);
    }

    /**
     * Vide le panier de l'utilisateur.
     */
    public function clearCart(User $user): void
    {
        $cartItems = $this->panierRepository->findBy(['user' => $user]);

        if (!empty($cartItems)) {
            foreach ($cartItems as $item) {
                $this->entityManager->remove($item);
            }
            $this->entityManager->flush();
        }
    }
}

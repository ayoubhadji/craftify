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
    // Trouve le produit par son ID
    $produit = $this->produitRepository->find($produitId);
    if (!$produit || $produit->getStock() <= 0) {
        throw new \Exception("Le produit n'existe pas ou est en rupture de stock.");
    }

    // Vérifie si le produit est déjà dans le panier de l'utilisateur
    $panier = $this->panierRepository->findOneBy([
        'user'    => $user,
        'produit' => $produit
    ]);

    // Ajout du dump pour voir ce qui est récupéré
    //dump($produit, $user, $panier); die;

    if ($panier) {
        // Vérifie si on peut augmenter la quantité sans dépasser le stock
        if ($panier->getQuantity() < $produit->getStock()) {
            $panier->setQuantity($panier->getQuantity() + 1);
        } else {
            throw new \Exception("Stock insuffisant pour ajouter plus d'unités.");
        }
    } else {
        // Création d'une nouvelle entrée Panier
        $panier = new Panier();
        $panier->setUser($user);
        $panier->setProduit($produit);
        $panier->setQuantity(1);
        $this->entityManager->persist($panier);
    }

    // Toujours persister et flusher après modification
    $this->entityManager->persist($panier);
    $this->entityManager->flush();
}

    

    public function remove(int $produitId, User $user): void
    {
        $produit = $this->produitRepository->find($produitId);
        if (!$produit) {
            throw new \Exception("Produit introuvable !");
        }

        $panier = $this->panierRepository->findOneBy([
            'user'    => $user,
            'produit' => $produit
        ]);

        if ($panier) {
            $this->entityManager->remove($panier);
            $this->entityManager->flush();
        }
    }

    public function getCart(User $user): array
    {
        return $this->panierRepository->findBy(['user' => $user]);
    }

    public function getTotal(User $user): float
    {
        $total = 0;
        foreach ($this->getCart($user) as $item) {
            $total += $item->getProduit()->getPrix() * $item->getQuantity();
        }
        return $total;
    }

    public function clearCartAfterOrder(User $user): void
    {
        $cartItems = $this->panierRepository->findBy(['user' => $user]);
        foreach ($cartItems as $item) {
            $this->entityManager->remove($item);
        }
        $this->entityManager->flush();
    }

    public function clearCart(User $user): void
{
    $cartItems = $this->panierRepository->findBy(['user' => $user]);

    foreach ($cartItems as $item) {
        $this->entityManager->remove($item);
    }

    $this->entityManager->flush();
}
}

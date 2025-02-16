<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CartController extends AbstractController
{
    private CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    #[Route('/cart', name: 'cart_index')]
    public function index(Request $request): Response
    {
        $user = $request->getSession()->get('user');

        if (!$user || !$user instanceof User) {
            return $this->redirectToRoute('app_login'); // Redirige vers login si l'utilisateur n'est pas connecté
        }

        // Récupérer le panier
        $cartItems = $this->cartService->getCart($user);
        $total = $this->cartService->getTotal($user);

        return $this->render('cart/index.html.twig', [
            'cart' => $cartItems,
            'total' => $total,
        ]);
    }

    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add(Request $request, int $id): Response
    {
        $user = $request->getSession()->get('user');
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        try {
            $this->cartService->add($id, $user);
            $this->addFlash('success', 'Produit ajouté au panier.');
        } catch (\Exception $e) {
            $this->addFlash('danger', $e->getMessage());
        }

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/cart/remove/{id}', name: 'cart_remove')]
    public function remove(Request $request, int $id): Response
    {
        $user = $request->getSession()->get('user');
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        try {
            $this->cartService->remove($id, $user);
            $this->addFlash('success', 'Produit retiré du panier.');
        } catch (\Exception $e) {
            $this->addFlash('danger', $e->getMessage());
        }

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/cart/clear', name: 'cart_clear')]
    public function clear(): Response
    {
        $user = $this->getUser(); // Utilise getUser() pour récupérer l'utilisateur connecté
        if (!$user || !$user instanceof User) {
            return $this->redirectToRoute('app_login'); // Si l'utilisateur n'est pas connecté, on le redirige
        }

        // Vider le panier de l'utilisateur
        $this->cartService->clearCart($user);
        $this->addFlash('success', 'Votre panier a été vidé.');

        return $this->redirectToRoute('cart_index');
    }
}

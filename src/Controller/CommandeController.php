<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\User;


final class CommandeController extends AbstractController
{
    #[Route('/commande', name: 'app_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository): Response
    {
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }

    #[Route('/commande/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/commande/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        if (!$commande) {
            throw $this->createNotFoundException('Commande not found');
        }
    
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }
    

    #[Route('/commande/{id}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id, CommandeRepository $commandeRepository, EntityManagerInterface $entityManager): Response
    {
        $commande = $commandeRepository->find($id);

        if (!$commande) {
            throw $this->createNotFoundException('La commande avec l\'ID ' . $id . ' n\'existe pas.');
        }

        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/commande/{id}', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $commande->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/commande/valider', name: 'app_commande_valider', methods: ['POST'])]
public function validerCommande(SessionInterface $session, Request $request, EntityManagerInterface $entityManager): Response
{
    // Debugging session data
    $userId = $request->getSession()->get('user');
    dd($userId);

    if (!$userId) {
        $this->addFlash('danger', 'Vous devez être connecté pour valider une commande.');
        return $this->redirectToRoute('app_login');
    }

    // Récupérer l'utilisateur en base de données
    $user = $entityManager->getRepository(User::class)->find($userId);
    dd($user); // Debugging user data

    if (!$user) {
        $this->addFlash('danger', 'Utilisateur non trouvé.');
        return $this->redirectToRoute('app_login');
    }

    // Récupérer le panier depuis la session
    $panier = $session->get('panier', []);
    dd($panier); // Debugging cart data

    if (!is_array($panier) || empty($panier)) {
        $this->addFlash('danger', 'Votre panier est vide.');
        return $this->redirectToRoute('app_panier');
    }

    // Création de la commande
    $commande = new Commande();
    $commande->setDateCommande(new \DateTime());
    $commande->setStatut('En attente');
    $commande->setTotal($this->calculerTotal($panier));
    $commande->setClient($user);

    // Ajout des produits à la commande
    foreach ($panier as $item) {
        $produit = $entityManager->getRepository(Produit::class)->find($item['id']);
        dd($produit); // Debugging product data
        if (!$produit) {
            $this->addFlash('danger', 'Un produit dans votre panier n\'existe plus.');
            return $this->redirectToRoute('app_panier');
        }
        $commande->addProduit($produit);
    }

    // Sauvegarde en base
    $entityManager->persist($commande);
    $entityManager->flush();

    // Vider le panier après validation
    $session->remove('panier');
    $this->addFlash('success', 'Votre commande a bien été enregistrée !');

    return $this->redirectToRoute('app_commande_show', ['id' => $commande->getId()]);
}




    private function calculerTotal(array $panier): float
    {
        $total = 0;
        foreach ($panier as $item) {
            $total += $item['prix'] * $item['quantite'];
        }
        return $total;
    }
}

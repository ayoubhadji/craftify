<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



#[Route('/user')]
final class UserController extends AbstractController
{
    #[Route(name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, Request $request): Response
    {
        // Nombre d'utilisateurs par page
        $page = $request->query->getInt('page', 1); // Page courante (par défaut la page 1)
        $limit = 3; // 3 utilisateurs par page

        // Récupérer les paramètres de recherche et de tri
        $searchTerm = $request->query->get('search', ''); // Rechercher un terme (par défaut vide)
        $sortBy = $request->query->get('sortBy', 'nom'); // Tri par défaut sur "nom"
        $order = $request->query->get('order', 'asc'); // Tri par défaut en ordre croissant

        // Appliquer les filtres de recherche
        $criteria = [];
        if ($searchTerm) {
            $criteria = [
                'nom' => $searchTerm,
                'email' => $searchTerm,
                'role' => $searchTerm,  // Ajoutez d'autres critères de recherche ici
                'code' => $searchTerm,  // Par exemple, pour rechercher dans le code
                'tel' => $searchTerm,   // Pour rechercher dans le téléphone
                'date_join' => $searchTerm, 
                'sexe' => $searchTerm,  
                'date_naissance' => $searchTerm,
                'address' => $searchTerm // Pour rechercher dans l'adresse
            ];
        }

        // Récupérer les utilisateurs avec le tri et la recherche
        $queryBuilder = $userRepository->createQueryBuilder('u');

        // Si un terme de recherche est fourni, appliquer les conditions
        if ($searchTerm) {
            $orX = $queryBuilder->expr()->orX();

            foreach ($criteria as $field => $value) {
                $orX->add("u.$field LIKE :searchTerm");
            }

            $queryBuilder->where($orX)
                        ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        // Appliquer le tri
        $queryBuilder->orderBy('u.' . $sortBy, $order);


        // Pagination
        $totalUsers = count($userRepository->findAll()); // Total des utilisateurs
        $totalPages = ceil($totalUsers / $limit);

        $users = $queryBuilder
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        return $this->render('user/index.html.twig', [
            'users' => $users,
            'searchTerm' => $searchTerm,
            'sortBy' => $sortBy,
            'order' => $order,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }

   #[Route('/registre', name: 'app_register', methods: ['GET', 'POST'])]
public function register(Request $request, EntityManagerInterface $entityManager): Response
{
    $user = new User();
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted()) {
        if ($form->isValid()) {
            // Sauvegarde de l'utilisateur
            $entityManager->persist($user);
            $entityManager->flush();

            // Ajout du message de succès
            $this->addFlash('success', 'Registration successful!');
            return $this->redirectToRoute('app_login');
        } else {
            // Si le formulaire est invalide, affiche un message d'erreur global
            $this->addFlash('error', 'Please correct the errors in the form.');
        }
    }

    return $this->render('user/register.html.twig', [
        'user' => $user,
        'form' => $form->createView(),
    ]);
}


    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }


    

}

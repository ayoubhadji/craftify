<?php
namespace App\Controller;

use App\Entity\Post;
use App\Entity\Commentaire;
use App\Form\PostType;
use App\Form\CommentaireType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;

#[Route('/admin/post')]
class PostAdminController extends AbstractController
{
    // Display all posts in the admin back-office
    #[Route('/', name: 'admin_post_index', methods: ['GET'])]
    public function adminIndex(PostRepository $postRepository): Response
    {
        // Fetch all posts from the repository
        $posts = $postRepository->findAll();

        // Render the template with posts data
        return $this->render('postadmin/admin_index.html.twig', [
            'posts' => $posts,
        ]);
    }

    // Create a new post in the admin back-office
    #[Route('/new', name: 'admin_post_new', methods: ['GET', 'POST'])]
    public function adminNew(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Create a new Post entity instance
        $post = new Post();
        
        // Create the form for the post
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        // If form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the new post
            $entityManager->persist($post);
            $entityManager->flush();

            // Redirect to the post index page after creation
            return $this->redirectToRoute('admin_post_index');
        }

        // Render the new post form template
        return $this->render('postadmin/admin_post_new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Edit an existing post in the admin back-office
    #[Route('/{id}/edit', name: 'admin_post_edit', methods: ['GET', 'POST'])]
    public function adminEdit(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        // Create the form for editing the post
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        // If form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Update the post
            $entityManager->flush();

            // Redirect to the post index page after updating
            return $this->redirectToRoute('admin_post_index');
        }

        // Render the edit post form template
        return $this->render('postadmin/admin_post_edit.html.twig', [
            'form' => $form->createView(),
            'post' => $post,
        ]);
    }

    // Delete an existing post in the admin back-office
    #[Route('/{id}', name: 'admin_post_delete', methods: ['POST'])]
    public function adminDelete(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        // Check the CSRF token to validate the delete action
        if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->request->get('_token'))) {
            // Remove the post
            $entityManager->remove($post);
            $entityManager->flush();
        }

        // Redirect to the post index page after deletion
        return $this->redirectToRoute('admin_post_index');
    }

    // Display a single post in the admin back-office
    #[Route('/{id}', name: 'admin_post_show', methods: ['GET', 'POST'])]

public function show(Request $request, Post $post, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
{
    // Create new Comment object
    $commentaire = new Commentaire();

    // Fetch the default user (User with ID 1) or the logged-in user
    $user = $this->getUser();
    if (!$user) {
        $user = $userRepository->find(1);
    }

    // Set required fields
    $commentaire->setIdUser($user);
    $commentaire->setIdPost($post);
    $commentaire->setDateCommentaire(new \DateTime());
    $commentaire->setNmbLike(0);

    // Create the form
    $form = $this->createForm(CommentaireType::class, $commentaire);
    $form->handleRequest($request);

    // Handle form submission
    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($commentaire);
        $entityManager->flush();

        // Redirect to refresh comments after adding
        return $this->redirectToRoute('admin_post_show', ['id' => $post->getId()]);
    }

    return $this->render('postadmin/admin_post_show.html.twig', [
        'post' => $post,
        'form' => $form->createView(),
    ]);
}

    
}


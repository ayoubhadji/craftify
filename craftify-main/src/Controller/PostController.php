<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\CommentaireType;

use App\Entity\Post;
use App\Entity\Commentaire;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;


#[Route('/post')]
class PostController extends AbstractController
{

    
    #[Route('/', name: 'app_post_index', methods: ['GET'])]
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = new Post(); // ✅ The constructor now sets date & likes automatically
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $mediaFile */
            $mediaFile = $form->get('mediaFile')->getData();
    
            if ($mediaFile) {
                $newFilename = uniqid() . '.' . $mediaFile->guessExtension();
                try {
                    $mediaFile->move(
                        $this->getParameter('post_images_directory'), 
                        $newFilename
                    );
                    $post->setMediaUrl($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Failed to upload image.');
                }
            }
    
            $entityManager->persist($post);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_post_index');
        }
    
        return $this->render('post/new.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }
    


#[Route('/post/{id}', name: 'app_post_show', methods: ['GET', 'POST'])]
public function show(Request $request, Post $post, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
{
    // Create new Comment object
    $commentaire = new Commentaire();

    // Fetch the default user (User with ID 1)
    $user = $userRepository->find(1);
    if (!$user) {
        throw $this->createNotFoundException('User not found.');
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
        return $this->redirectToRoute('app_post_show', ['id' => $post->getId()]);
    }

    return $this->render('post/show.html.twig', [
        'post' => $post,
        'form' => $form->createView(),
    ]);
}








    #[Route('/{id}/edit', name: 'app_post_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $mediaFile */
            $mediaFile = $form->get('mediaFile')->getData();
            if ($mediaFile) {
                $newFilename = uniqid() . '.' . $mediaFile->guessExtension();

                try {
                    // Move the file to the directory where you want to store it
                    $mediaFile->move(
                        $this->getParameter('post_images_directory'), // Specify the directory
                        $newFilename
                    );
                    $post->setMediaUrl($newFilename);
                } catch (FileException $e) {
                    // Handle file upload error
                    $this->addFlash('error', 'Failed to upload image.');
                }
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('post/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_post_delete', methods: ['POST'])]
    public function delete(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->request->get('_token'))) {
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
    }

   
}


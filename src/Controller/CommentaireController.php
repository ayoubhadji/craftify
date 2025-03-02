<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commentaire')]
class CommentaireController extends AbstractController
{
    /** ====================== ✅ FRONT OFFICE ROUTES ====================== **/

    #[Route('/', name: 'app_commentaire_index', methods: ['GET'])]
    public function index(CommentaireRepository $commentaireRepository): Response
    {
        return $this->render('commentaire/index.html.twig', [
            'commentaires' => $commentaireRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_commentaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setDateCommentaire(new \DateTime()); // ✅ Auto-fill comment date
            $commentaire->setNmbLike(0); // ✅ Auto-fill likes as 0

            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_post_show', ['id' => $commentaire->getIdPost()->getId()]);
        }

        return $this->render('commentaire/new.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commentaire_show', methods: ['GET'])]
    public function show(Commentaire $commentaire): Response
    {
        return $this->render('commentaire/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commentaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_post_show', ['id' => $commentaire->getIdPost()->getId()]);
        }

        return $this->render('commentaire/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_commentaire_delete', methods: ['POST'])]
    public function delete(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentaire->getId(), $request->request->get('_token'))) {
            $postId = $commentaire->getIdPost()->getId(); // ✅ Get post ID before deletion
            $entityManager->remove($commentaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_post_show', ['id' => $postId]); // ✅ Redirect to the post
        }

        return $this->redirectToRoute('app_post_index'); // Default fallback
    }

    /** ====================== ✅ BACK OFFICE ROUTES (ADMIN) ====================== **/

    #[Route('/admin/commentaires', name: 'admin_commentaire_index', methods: ['GET'])]
public function adminIndex(CommentaireRepository $commentaireRepository): Response
{
    // Fetch all comments from the database
    $commentaires = $commentaireRepository->findAll();

    // Pass the 'commentaires' variable to the view for rendering
    return $this->render('back/commentaire/index.html.twig', [
        'commentaires' => $commentaires,
    ]);
}


    #[Route('/admin/edit/{id}', name: 'admin_commentaire_edit', methods: ['GET', 'POST'])]
    public function adminEdit(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        return $this->edit($request, $commentaire, $entityManager);
    }

    #[Route('/admin/delete/{id}', name: 'admin_commentaire_delete', methods: ['POST'])]
    public function adminDelete(Request $request, CommentaireRepository $commentaireRepository, EntityManagerInterface $entityManager, int $id): Response
    {
        $commentaire = $commentaireRepository->find($id);
        if (!$commentaire) {
            throw $this->createNotFoundException("Comment not found.");
        }

        $postId = $commentaire->getIdPost()->getId(); // ✅ Get post ID before deletion

        if ($this->isCsrfTokenValid('delete'.$commentaire->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_post_show', ['id' => $postId]); // ✅ Redirect to post in admin
    }
}

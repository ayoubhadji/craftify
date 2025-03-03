<?php

namespace App\Controller;

use App\Entity\AventurierQuiz;
use App\Form\AventurierQuizType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/aventurier/quiz')]
final class AventurierQuizController extends AbstractController
{
    #[Route(name: 'app_aventurier_quiz_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $aventurierQuizzes = $entityManager
            ->getRepository(AventurierQuiz::class)
            ->findAll();

        return $this->render('aventurier_quiz/index.html.twig', [
            'aventurier_quizzes' => $aventurierQuizzes,
        ]);
    }

    #[Route('/new', name: 'app_aventurier_quiz_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $aventurierQuiz = new AventurierQuiz();
        $form = $this->createForm(AventurierQuizType::class, $aventurierQuiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($aventurierQuiz);
            $entityManager->flush();

            return $this->redirectToRoute('app_aventurier_quiz_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('aventurier_quiz/new.html.twig', [
            'aventurier_quiz' => $aventurierQuiz,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_aventurier_quiz_show', methods: ['GET'])]
    public function show(AventurierQuiz $aventurierQuiz): Response
    {
        return $this->render('aventurier_quiz/show.html.twig', [
            'aventurier_quiz' => $aventurierQuiz,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_aventurier_quiz_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AventurierQuiz $aventurierQuiz, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AventurierQuizType::class, $aventurierQuiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_aventurier_quiz_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('aventurier_quiz/edit.html.twig', [
            'aventurier_quiz' => $aventurierQuiz,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_aventurier_quiz_delete', methods: ['POST'])]
    public function delete(Request $request, AventurierQuiz $aventurierQuiz, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$aventurierQuiz->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($aventurierQuiz);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_aventurier_quiz_index', [], Response::HTTP_SEE_OTHER);
    }
}

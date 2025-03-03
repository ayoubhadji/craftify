<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Quiz;
use App\Entity\Expedition;
use App\Entity\Aventurier;
use App\Entity\AventurierExpedition;
use App\Entity\AventurierQuiz;
use App\Entity\Questions;
use App\Entity\Reponses;
use App\Form\QuizType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Part\TextPart;
use Symfony\Component\Mime\Part\File;

#[Route('/quiz')]
final class QuizController extends AbstractController
{
    #[Route(name: 'app_quiz_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $quizzes = $entityManager->getRepository(Quiz::class)->findAll();

        return $this->render('quiz/index.html.twig', [
            'quizzes' => $quizzes,
        ]);
    }

    #[Route('/new/{expeditionId}', name: 'app_quiz_new', methods: ['GET', 'POST'])]
    public function new(int $expeditionId, Request $request, EntityManagerInterface $entityManager): Response
    {
        $expedition = $entityManager->getRepository(Expedition::class)->find($expeditionId);
        if (!$expedition) {
            throw $this->createNotFoundException('Expédition non trouvée.');
        }

        $quiz = new Quiz();
        $quiz->setExpedition($expedition);

        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($quiz);
            $entityManager->flush();

            return $this->redirectToRoute('app_quiz_index');
        }

        return $this->render('quiz/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_quiz_show', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $entityManager): Response
    {
        $quiz = $entityManager->getRepository(Quiz::class)->find($id);
        if (!$quiz) {
            throw $this->createNotFoundException("Le quiz avec l'ID $id n'existe pas.");
        }

        return $this->render('quiz/show.html.twig', [
            'quiz' => $quiz,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_quiz_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Quiz $quiz, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_quiz_index');
        }

        return $this->render('quiz/edit.html.twig', [
            'quiz' => $quiz,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_quiz_delete', methods: ['POST'])]
    public function delete(Request $request, Quiz $quiz, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $quiz->getId(), $request->request->get('_token'))) {
            $entityManager->remove($quiz);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_quiz_index');
    }
    #[Route('/passer/{quizId}/{id}', name: 'app_quiz_passer', methods: ['GET', 'POST'])]
    public function quizPasser(int $quizId, int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'aventurier
        $aventurier = $entityManager->getRepository(Aventurier::class)->find($id);
        if (!$aventurier) {
            throw $this->createNotFoundException('Aventurier non trouvé.');
        }
    
        // Récupérer l'expédition associée à cet aventurier
        $expedition = $entityManager->getRepository(AventurierExpedition::class)->findOneBy(['aventurier' => $aventurier]);
        if (!$expedition) {
            throw $this->createNotFoundException('Expédition non trouvée pour cet aventurier.');
        }
    
        // Accéder à la collection de quiz associés à cette expédition
        $quizzes = $expedition->getExpedition()->getQuizzes();
        if ($quizzes->isEmpty()) {
            throw $this->createNotFoundException('Aucun quiz trouvé pour cette expédition.');
        }
    
        // Trouver le quiz avec l'ID fourni
        $quiz = $quizzes->filter(fn($q) => $q->getId() === $quizId)->first();
        if (!$quiz) {
            throw $this->createNotFoundException('Quiz non trouvé pour cet ID.');
        }
    
        // Récupérer les questions du quiz
        $questions = $quiz->getQuestions();
    
        // Créer une instance d'AventurierQuiz pour enregistrer les résultats
        $aventurierQuiz = new AventurierQuiz();
        $aventurierQuiz->setAventurier($aventurier);
        $aventurierQuiz->setQuiz($quiz);
        $aventurierQuiz->setDatePassage(new \DateTime());
        $aventurierQuiz->setStatut('en cours');
    
        $score = 0;
    
        if ($request->isMethod('POST')) {
            // Traiter les réponses du quiz
            foreach ($questions as $question) {
                $reponseId = $request->request->get("reponse_{$question->getId()}");
                if ($reponseId) {
                    // Récupérer la réponse de l'utilisateur
                    $reponseEntity = $entityManager->getRepository(Reponses::class)->find($reponseId);
    
                    // Vérifier si la réponse est correcte
if ($reponseEntity && $reponseEntity->getIsCorrect()) {
    // Incrémenter le score si la réponse est correcte
    $score++;
}

                }
            }
    
            // Calcul du score et pourcentage
            $totalQuestions = count($questions);
            $pourcentage = ($totalQuestions > 0) ? ($score / $totalQuestions) * 100 : 0;
    
            $aventurierQuiz->setScore($score);
            $aventurierQuiz->setStatut('terminé');
    
            // Vérification du seuil pour délivrer un certificat
            if ($pourcentage >= 70) {
                $aventurierQuiz->setCertificatDelivre(true);
            } else {
                $aventurierQuiz->setCertificatDelivre(false);
            }
    
            // Enregistrer les résultats du quiz
            $entityManager->persist($aventurierQuiz);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_quiz_resultats', ['id' => $aventurierQuiz->getId()]);
        }
    
        return $this->render('quiz/passer.html.twig', [
            'quiz' => $quiz,
            'questions' => $questions,
        ]);
    }
    
    
    
    #[Route('/resultats/{id}', name: 'app_quiz_resultats', methods: ['GET'])]
    public function resultats(int $id, EntityManagerInterface $entityManager): Response
    {
        $AventurierQuiz = $entityManager->getRepository(AventurierQuiz::class)->find($id);
        if (!$AventurierQuiz) {
            throw $this->createNotFoundException('AventurierQuiz non trouvé');
        }

        $quiz = $AventurierQuiz->getQuiz();

        return $this->render('quiz/resultats.html.twig', [
            'AventurierQuiz' => $AventurierQuiz,
            'quiz' => $quiz,
            'certificatDisponible' => $AventurierQuiz->isCertificatDelivre(),
        ]);
    }
    #[Route('/certificat/telecharger/{id}', name: 'app_quiz_download_certificat', methods: ['GET'])]
public function downloadCertificat(int $id, EntityManagerInterface $entityManager): Response
{
    // Récupère l'objet AventurierQuiz en fonction de l'id passé dans l'URL
    $AventurierQuiz = $entityManager->getRepository(AventurierQuiz::class)->find($id);
    
    // Si le certificat n'est pas délivré ou l'aventurier n'existe pas, retourne une erreur
    if (!$AventurierQuiz || !$AventurierQuiz->isCertificatDelivre()) {
        throw $this->createNotFoundException('Certificat non disponible.');
    }

    // Génère le certificat PDF
    $pdfPath = $this->generateCertificat($AventurierQuiz);

    // Vérifie si le PDF a bien été généré
    if (!file_exists($pdfPath)) {
        throw new \Exception('Le certificat PDF n\'a pas pu être généré.');
    }

    // Récupère le contenu du fichier PDF pour le renvoyer dans la réponse
    $pdfContent = file_get_contents($pdfPath);

    // Crée la réponse avec le contenu PDF
    return new Response($pdfContent, 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'attachment; filename="certificat_' . $AventurierQuiz->getId() . '.pdf"',
    ]);
}

public function generateCertificat($AventurierQuiz)
{
    // Initialize DomPDF
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isPhpEnabled', true);
    $dompdf = new Dompdf($options);
    
    // Fetch data from AventurierQuiz
    $aventurier = $AventurierQuiz->getAventurier();
    $quiz = $AventurierQuiz->getQuiz();
    $expedition = $quiz->getExpedition();
    $score = $AventurierQuiz->getScore();
    $datePassage = $AventurierQuiz->getDatePassage()->format('d-m-Y');
    $certificatDelivre = $AventurierQuiz->isCertificatDelivre();
    $currentDate = new \DateTime();
    $currentDate = $currentDate->format('d-m-Y');
    
    // Prepare HTML content for the certificate
    $html = $this->renderView('quiz/certificat.html.twig', [
        'aventurier' => $aventurier,
        'quiz' => $quiz,
        'expedition' => $expedition,
        'score' => $score,
        'datePassage' => $datePassage,
        'certificat_delivre' => $certificatDelivre,
        'currentDate' => $currentDate,
    ]);

    // Load HTML content into DomPDF
    $dompdf->loadHtml($html);
    
    $dompdf->setPaper('A4', 'landscape');

    // Render PDF (first pass)
    $dompdf->render();
    $currentDate = new \DateTime();
    // Output the generated PDF (path or inline)
    $pdfPath = $this->getParameter('kernel.project_dir') . '/public/uploads/' . $AventurierQuiz->getId() . '.pdf';

    file_put_contents($pdfPath, $dompdf->output());

    return $pdfPath;
}

    
}
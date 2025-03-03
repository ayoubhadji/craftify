<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface;

class FeedbackController extends AbstractController
{
    /**
     * @Route("/submit-feedback", name="feedback_submit", methods={"POST"})
     */
    public function submitFeedback(Request $request, LoggerInterface $logger)
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['feedback']) && !empty($data['feedback'])) {
            try {
                // Log the feedback content to check what's coming in
                $logger->info('Received feedback: ' . $data['feedback']);
                
                // Simulate saving feedback to a database
                // You can implement your actual feedback saving logic here

                return new JsonResponse(['message' => 'Thank you for your feedback!'], 200);
            } catch (\Exception $e) {
                // Log the exception message
                $logger->error('Error saving feedback: ' . $e->getMessage());
                return new JsonResponse(['error' => 'There was an error saving your feedback. Please try again.'], 500);
            }
        }

        return new JsonResponse(['error' => 'No feedback provided!'], 400);
    }
}
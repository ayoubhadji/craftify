<?php

namespace App\Controller;

use App\Form\ForgotPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Psr\Log\LoggerInterface;

class ForgotPasswordController extends AbstractController
{
    #[Route('/forgot-password', name: 'app_forgot_password')]
    public function forgotPassword(
        Request $request,
        UserRepository $userRepository,
        TokenGeneratorInterface $tokenGenerator,
        MailerInterface $mailer,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    ): Response {
        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);
        $error = null;

        // First, check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $user = $userRepository->findOneBy(['email' => $email]);

            if (!$user) {
                $error = 'Aucun compte trouvé avec cet email.';
            } else {
                try {
                    // Generate reset token
                    $token = $tokenGenerator->generateToken();
                    $user->setResetToken($token);
                    $entityManager->persist($user);
                    $entityManager->flush();

                    // Generate reset password URL
                    $resetUrl = $this->generateUrl('app_reset_password', ['token' => $token], \Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_URL);

                    // Send email with reset link
                    $emailMessage = (new Email())
                        ->from('noreply@tonsite.com')
                        ->to($user->getEmail())
                        ->subject('Réinitialisation de votre mot de passe')
                        ->html("<p>Cliquez sur le lien ci-dessous pour réinitialiser votre mot de passe :</p><p><a href='$resetUrl'>$resetUrl</a></p>");

                    $mailer->send($emailMessage);

                    // Success message
                    $this->addFlash('success', 'Un email a été envoyé.');
                    return $this->redirectToRoute('app_login');
                } catch (\Exception $e) {
                    $logger->error('Erreur d\'envoi d\'email : ' . $e->getMessage());
                    $error = 'Une erreur est survenue.';
                }
            }
        }

        // Render the form with error message if needed
        return $this->render('user/forgot.html.twig', [
            'form' => $form->createView(),
            'error' => $error,
        ]);
    }
}

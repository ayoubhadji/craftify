<?php

namespace App\Controller;

use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends AbstractController
{
    #[Route('/reset-password/{token}', name: 'app_reset_password')]
    public function resetPassword(
        string $token,
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        $error = null;

        // Find the user by the reset token
        $user = $userRepository->findOneBy(['resetToken' => $token]);

        // If no user found or token has expired
        if (!$user) {
            $error = 'Lien invalide ou expiré.';
            return $this->render('user/resetcode.html.twig', [
                'error' => $error,
                'token' => $token,
            ]);
        }

        // Create and handle the form
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $newPassword = $data['code'];
            $confirmPassword = $data['confirm_password'];

            // Check if the passwords match
            if ($newPassword !== $confirmPassword) {
                $error = 'Les mots de passe ne correspondent pas.';
                return $this->render('user/resetcode.html.twig', [
                    'form' => $form->createView(),
                    'error' => $error, // Pass error to template
                    'token' => $token,
                ]);
            }

            // Hash and save the new password
            $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
            $user->setPassword($hashedPassword);

            // Clear the reset token and expiration time after successful password update
            $user->setResetToken(null);
            $user->setResetTokenExpiresAt(null);

            // Persist changes to the database
            $entityManager->flush();  // You don’t need to call persist if it’s already managed

            // Redirect to the login page with a success message
            $this->addFlash('success', 'Votre mot de passe a été réinitialisé avec succès!');
            return $this->redirectToRoute('app_login');
        }

        // Render the form and pass any error messages
        return $this->render('user/resetcode.html.twig', [
            'form' => $form->createView(),
            'error' => $error, // Pass error if any
            'token' => $token, // Pass the token to the template if you need to use it
        ]);
    }
}

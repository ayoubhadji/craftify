<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


final class AuthController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Vérifier si l'utilisateur est déjà connecté
        $user = $request->getSession()->get('user');
        if ($user) {
            // Si l'utilisateur est déjà connecté, redirige-le vers la page appropriée
            switch ($user->getRole()) {
                case 'USER':
                    return $this->redirectToRoute('homelog');
                case 'ARTISAN':
                    return $this->redirectToRoute('homelog');
                case 'ADMIN':
                    return $this->redirectToRoute('app_back');
                default:
                    // Si rôle invalide, déconnecte l'utilisateur et redirige vers la page de connexion
                    $request->getSession()->remove('user');
                    return $this->redirectToRoute('app_login');
            }
        }

        $error = null;
        $lastEmail = '';

        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $password = $request->request->get('password');

            $lastEmail = $email;

            // Récupérer l'utilisateur depuis la base de données
            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

            if (!$user || $user->getCode() !== $password) {
                $error = 'Email ou mot de passe incorrect.';
            } else {
                // L'utilisateur est connecté, démarrons la session
                $request->getSession()->set('user', $user); // Store user object in session

                // Vérification du rôle et redirection
                switch ($user->getRole()) {
                    case 'USER':
                        return $this->redirectToRoute('homelog');
                    case 'ARTISAN':
                        return $this->redirectToRoute('homelog');
                    case 'ADMIN':
                        return $this->redirectToRoute('app_back');
                    default:
                        $error = "Rôle invalide.";
                }
            }
        }

        return $this->render('auth/login.html.twig', [
            'error' => $error,
            'last_email' => $lastEmail,
            'recaptcha_site_key' => $_ENV['RECAPTCHA3_KEY'],
            'recaptcha_site_key2' => $_ENV['RECAPTCHA2_KEY']
            
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(Request $request): Response
    {
        // Supprimer l'utilisateur de la session
        $request->getSession()->remove('user');

        // Rediriger l'utilisateur vers la page de connexion ou autre page
        return $this->redirectToRoute('app_login');
    }

    #[Route('/profile', name: 'app_profile')]
    public function profile(Request $request): Response
    {
        // Récupérer l'utilisateur connecté depuis la session
        $user = $request->getSession()->get('user');

        // Vérifier si un utilisateur est connecté
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Rendre la vue de profil avec les informations de l'utilisateur
        return $this->render('auth/profile.html.twig', [
            'user' => $user
        ]);
    }


        #[Route('/editprofile', name: 'profil_edit', methods: ['GET', 'POST'])]
    public function editprofile(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'utilisateur connecté depuis la session
        $user = $request->getSession()->get('user');

        // Vérifier si un utilisateur est connecté
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Récupération de l'utilisateur depuis la base de données (évite les problèmes d'objet détaché)
        $user = $entityManager->getRepository(User::class)->find($user->getId());

        if (!$user) {
            return $this->redirectToRoute('app_login'); // Sécurité supplémentaire
        }

        // Créer le formulaire
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            // Mettre à jour l'utilisateur dans la session
            $request->getSession()->set('user', $user);

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('auth/mod.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }


}

<?php

namespace App\Controller;

use App\Entity\Reaction;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\ReactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/reaction')]
class ReactionController extends AbstractController
{
    #[Route('/toggle/{postId}/{type}', name: 'toggle_reaction', methods: ['POST'])]
    public function toggleReaction(
        int $postId,
        string $type,
        EntityManagerInterface $entityManager,
        Security $security,
        ReactionRepository $reactionRepository
    ): JsonResponse {
        try {
            // Get authenticated user
            $user = $security->getUser();

            // If no authenticated user, assign a default user with ID 1
            if (!$user) {
                $user = $entityManager->getRepository(User::class)->find(1);
                if (!$user) {
                    return new JsonResponse(['error' => 'Default user not found'], 403);
                }
            }

            // Find the post
            $post = $entityManager->getRepository(Post::class)->find($postId);
            if (!$post) {
                return new JsonResponse(['error' => 'Post not found'], 404);
            }

            // Find existing reaction
            $reaction = $reactionRepository->findOneBy(['id_user' => $user, 'id_post' => $post]);

            if ($reaction) {
                if ($reaction->getType() === $type) {
                    $entityManager->remove($reaction);
                } else {
                    $reaction->setType($type);
                }
            } else {
                $reaction = new Reaction();
                $reaction->setIdUser($user);
                $reaction->setIdPost($post);
                $reaction->setType($type);
                $entityManager->persist($reaction);
            }

            $entityManager->flush();

            return new JsonResponse([
                'likes' => $post->getTotalLikes(),
                'dislikes' => $post->getTotalDislikes()
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
}

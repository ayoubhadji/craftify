<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AventurierExpeditionController extends AbstractController
{
    #[Route('/aventurier/expedition', name: 'app_aventurier_expedition')]
    public function index(): Response
    {
        return $this->render('aventurier_expedition/index.html.twig', [
            'controller_name' => 'AventurierExpeditionController',
        ]);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomelogController extends AbstractController
{
    #[Route('/', name: 'homelog')]
    public function index(): Response
    {
        return $this->render('homelog/index.html.twig');
    }

    #[Route('/About', name: 'About')]
    public function about(): Response
    {
        return $this->render('About/About.html.twig');
    }


#[Route('/404', name: '404')]
    public function notfound(): Response
    {
        return $this->render('error/notfound.html.twig' );
    }


}

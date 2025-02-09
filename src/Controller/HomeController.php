<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    
    #[Route('/home', name: 'homepage')]
    public function home(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/About', name: 'About')]
    public function about(): Response
    {
        return $this->render('About/About.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }


#[Route('/404', name: '404')]
    public function notfound(): Response
    {
        return $this->render('error/notfound.html.twig' );
    }

}

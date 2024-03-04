<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// class HomeController => pour expliquer qu'on peut spécifier les routes
class HomeController extends AbstractController
{
    // #[Route('/home', name: 'app_home')]
    #[Route('/', name: 'home')]
    function index(Request $request): Response
    {
        // return new Response('Bonjour les gens');
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}

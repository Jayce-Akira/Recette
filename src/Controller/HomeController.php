<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

// class HomeController => pour expliquer qu'on peut spécifier les routes
class HomeController extends AbstractController
{
    // #[Route('/home', name: 'app_home')]
    #[Route('/', name: 'home')]
    function index(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        // $user = new User();
        // $user->setEmail('john@doe.fr')
        //     ->setUsername('JohnDoe')
        //     ->setPassword($hasher->hashPassword($user, '0000'))
        //     ->setRoles([]);
        // $em->persist($user);
        // $em->flush();
        // return new Response('Bonjour les gens');
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}

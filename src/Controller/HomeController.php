<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller
{
     /**
     * @Route("/", name="root")
     */
    public function root(){
        return $this->redirectToRoute('home');
    }
    
    /**
     * @Route("/home", name="home")
     * @Route("page/{page}", name="home_paginated")
     */
    public function index(ProductRepository $ProductRepo, $page = 1) // $UserRepo est passer automatiquement en paramatre grace a symfony c'est ce qui s'appelle la dependance, on a donc pas a l'instancier nous-même.
    {
        $products = $ProductRepo->findPaginated($page);
        return $this->render('home.html.twig', [
        'products' => $products
    ]);
    }
}

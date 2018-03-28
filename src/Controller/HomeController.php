<?php

namespace App\Controller;

use App\Repository\UserRepository;
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
     */
    public function index(UserRepository $UserRepo) // $UserRepo est passer automatiquement en paramatre grace a symfony c'est ce qui s'appelle la dependance, on a donc pas a l'instancier nous-même.
    {
        // $userRepo effectue ici un SELECT * FROM user ...
        return $this->render('home.html.twig');
    }
}

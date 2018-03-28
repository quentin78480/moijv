<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    /**
     * @Route("/admin/dashboard", name="admin_dashboard")
     */
    public function index(UserRepository $UserRepo)
// $UserRepo est passer automatiquement en paramatre grace a symfony c'est ce qui s'appelle la dependance, on a donc pas a l'instancier nous-même.
    {
        // $userRepo effectue ici un SELECT * FROM user ...
        $userList = $UserRepo ->findAll();
        return $this->render('admin/dashboard.html.twig', ['users' => $userList]);
    }
}

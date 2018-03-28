<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

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
    
     /**
     * @Route("/admin/user/delete/{id}", name="delete_user")
     */
    public function deleteUser(User $user, ObjectManager $manager){
        $manager->remove($user);
        $manager->flush();
        return $this->redirectToRoute('admin_dashboard');
    }
}

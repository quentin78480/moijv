<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
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
    
     /**
     * @Route("/admin/user/add", name="add_user")
     * @Route("/admin/user/edit/{id}", name="edit_user")
     */
    public function editUser(Request $request, ObjectManager $manager, User $user = null){
        if ($user == null){
            $user = new User();
        }
        $formUser = $this->createForm(UserType::class, $user)
                ->add('Envoyer',SubmitType::class);
        
        //validation du formulaire
        $formUser->handleRequest($request); // declenche la gestion du formulaire
        if($formUser->isSubmitted() && $formUser->isValid()) // si il est soumit et si il est valide
        {
            // enregistrement de notre utilisateur
            $user->setRegisterDate(new \DateTime('now'));
            $user->setRoles('ROLE_USER');
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('admin_dashboard');
        }
        
        return $this->render('admin/edit_user.html.twig', [
            'form' => $formUser->createView()
       ]);
    }
}

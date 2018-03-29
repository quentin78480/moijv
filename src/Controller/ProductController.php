<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends Controller
{
    /**
     * @Route("/product", name="product")
     */
    public function index(ProductRepository $productRepository)
    {
        $listProduct = $productRepository ->findAll();
        return $this->render('product/product.html.twig', ['product' => $listProduct]);
    }
    
     /**
     * @Route("/product/delete/{id}", name="delete_product")
     */
    public function deleteUser(Product $product, ObjectManager $manager){
        $manager->remove($product);
        $manager->flush();
        return $this->redirectToRoute('product');
    }
    
     /**
     * @Route("/admin/product/add", name="add_product")
     * @Route("/admin/product/edit/{id}", name="edit_product")
     */
    public function editUser(Request $request, ObjectManager $manager, Product $product = null){
        if ($product == null){
            $product = new Product();
        }
        $formProduct = $this->createForm(ProductType::class, $product)
                ->add('Envoyer',SubmitType::class);
        
        //validation du formulaire
        $formProduct->handleRequest($request); // declenche la gestion du formulaire
        if($formProduct->isSubmitted() && $formProduct->isValid()) // si il est soumit et si il est valide
        {
            // enregistrement de notre utilisateur
            $manager->persist($product);
            $manager->flush();
            return $this->redirectToRoute('product');
        }
        
        return $this->render('product/edit_product.html.twig', [
            'form' => $formProduct->createView()
       ]);
    }
}

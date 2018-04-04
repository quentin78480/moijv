<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product")  
 * 
 */

class ProductController extends Controller
{
    /**
     * @Route("/", name="product")
     * @Route("/{page}", name="product_paginated", requirements={"page"="\d+"})
     */
    public function index(ProductRepository $productRepository, $page =1)
    {
        $listProduct = $productRepository ->findPaginatedByUser($this->getUser(), $page);
        return $this->render('product/product.html.twig', ['products' => $listProduct]);
    }
     /**
     * @Route("/product/delete/{id}", name="delete_product")
     */
    public function deleteUser(Product $product, ObjectManager $manager){
        if($product->getOwner()->getId() !== $this->getUser()->getId()){
            throw $this->createAccessDeniedException('You\'re not allowed to delete this product');
        }
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
            $group = 'insertion';
        } else {
            $oldImage = $product->getImage();
            $product->setImage(new File($product->getImage()));
            $group = 'édition';
        }
        $formProduct = $this->createForm(ProductType::class, $product, ['validation_groups'=>[$group]])
                ->add('Envoyer',SubmitType::class);
        
        //validation du formulaire
        $formProduct->handleRequest($request); // declenche la gestion du formulaire
        if($formProduct->isSubmitted() && $formProduct->isValid()) // si il est soumit et si il est valide
        {
            // enregistrement de notre utilisateur
            $product->setOwner($this->getUser());
            $image = $product ->getImage();
            if( $image == null){
                $product->setImage($oldImage);
            } else {
                $newFileName = md5(uniqid()) . '.' . $image->guessExtension();
                $image ->move('upload', $newFileName);
                $product->setImage('upload/'.$newFileName); 
            }

            $manager->persist($product);
            $manager->flush();
            return $this->redirectToRoute('product');
        }
        
        return $this->render('product/edit_product.html.twig', [
            'form' => $formProduct->createView()
       ]);
    }
    
}

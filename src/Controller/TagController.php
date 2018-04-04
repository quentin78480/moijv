<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


    /**
     * @Route("/tag")
     */
class TagController extends Controller
{
    /**
     * @Route("/{slug}/product", name="tag")
     */
    public function product()
    {
        return $this->render('product.html.twig', [
            'controller_name' => 'TagController',
        ]);
    }
}

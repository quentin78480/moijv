<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Repository\ProductRepository;
use App\Repository\TagRepository;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


    /**
     * @Route("/tag")
     */
class TagController extends Controller
{
    /**
     * @Route("/{slug}/product", name="tag")
     * @Route("/{slug}/product/{page}", name="tag_paginated" )
     */
    public function product(ProductRepository $ProductRepo, Tag $tag, $page = 1)
    {
        $tagProductList = $ProductRepo ->findPaginatedByTag($tag , $page);
        return $this->render('tag/product.html.twig', [
            'tag' => $tag,
            'products' => $tagProductList
        ]);
    }
    
    /**
     * 
     * @Route("", name="search_tag")
     */
    public function search(TagRepository $tagRepo, Request $request) {
        $search = $request->query->get('search'); // $_GET['search']
        if(! $search){
            throw $this->createNotFoundException();
        }
        $slugify = new Slugify();
        $slug = $slugify->slugify($search);
        $searchTags = $tagRepo ->searchBySlug($slug);
        $formatedTagArray = [];
        foreach ($searchTags as $tag) {
            $formatedTagArray[] = ['name' => $tag->getName(), 'slug' => $tag->getSlug()];
        }
        return $this->json($formatedTagArray);
    }
}

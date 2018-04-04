
// ProductRepository \\

public function findPaginated($page = 1)
{
    $queryBuilder = $sthis->createQueryBuilder('p')->orderBy('p.id', 'ASC');
    $pager = new Doctrine\Adapter\DoctrineORMAdapter($queryBuilder);
    $pager = new \Pagerfanta\Pagerfanta($pager);
    return new $pager->setMaxPerPage(10)->setCurrentPage($page);
}

// ProductController \\

    /**
     * @Route("/product", name="product")
     * @Route("/product/{page}", name="product_paginated")
     */
    public function index(ProductRepository $productRepository, $page =1)
    {
        $listProduct = $productRepository ->findPaginated($page);
        return $this->render('product/product.html.twig', ['product' => $listProduct]);
    }


// product.html.twig \\

    {% if products haveToPaginate %}
{{ pagerfanta(products, {'routeName' : 'product_paginated'}) }} 

    {% endif %}

    A Faire :
        composer require white-october/pagerfanta-bundle
        

    /*

    */



















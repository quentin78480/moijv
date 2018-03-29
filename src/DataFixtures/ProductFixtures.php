<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 0 ; $i < 60; $i++){
        $product = new Product();
        $product->setDescription('Ceci est la description du produit n°'.$i);
        $product->setTitle('Article n°'.$i);
        $manager->persist($product);

        $manager->flush();
        }
    }
}

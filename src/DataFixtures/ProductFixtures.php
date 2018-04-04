<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for($i = 0 ; $i < 60; $i++){
        $product = new Product();
        $product->setDescription('Ceci est la description du produit n°'.$i);
        $product->setTitle('Article n°'.$i);
        $product->setOwner($this->getReference('user'. rand(0, 59)));
        $product->setImage("upload/500x325.png");
        $manager->persist($product);
        

        $manager->flush();
        }
    }
    public function getDependencies(): array {
        return
        [
            UserFixtures::class
        ];
    }
}

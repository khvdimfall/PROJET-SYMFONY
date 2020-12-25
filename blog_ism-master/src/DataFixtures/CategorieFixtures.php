<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CategorieFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        
        // $manager->persist($product);
        for ($i=1; $i < 20; $i++) {
            $cat=new Categorie();
            $cat->setLibelle("Categorie".$i);
            $manager->persist($cat);
        }
        


        $manager->flush();
    }
}

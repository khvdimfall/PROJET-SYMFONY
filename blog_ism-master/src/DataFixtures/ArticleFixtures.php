<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Repository\CategorieRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ArticleFixtures extends Fixture
{
    private $repo;
    public function __construct(CategorieRepository $repo)
    {
        $this->repo=$repo;
    }
    public function load(ObjectManager $manager)
    {
        $categories=$this->repo->findAll();
        foreach ($categories as $key => $categorie) {
            for ($i=0; $i <10 ; $i++) {
                $article=new Article();
                $article->setTitre("Article".$i);
                $article->setContenu("Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...");
                $article->setCategorie($categorie);

                $manager->persist($article);
            }
        }

        $manager->flush();
    }
}

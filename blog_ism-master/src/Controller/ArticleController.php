<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
     * @Route("/article")
     */

class ArticleController extends AbstractController
{
    /**
     * @Route("/show", name="article_show")
     */
    public function show(ArticleRepository $repo, Request $request, EntityManagerInterface $manager): Response
    {
        if (! $this->isGranted("ROLE_REDACTEUR")) {
            return $this->redirectToRoute("erreur_403");
        }
        $this->isGranted("ROLE_REDACTEUR");
        $articles=$repo->findAll();
       
        return $this->render('article/index.html.twig', [
            'articles' =>$articles
        ]);
    }



    /**
     * @Route("/show/statut/{statut?}", name="article_show_statut")
     * @IsGranted("ROLE_REDACTEUR")
     */
    public function showArticleByStatut($statut, ArticleRepository $repo, Request $request, EntityManagerInterface $manager): Response
    {
        $articles=$repo->findBy([
            "statut"=>$statut
        ]);
       
        return $this->render('article/index.html.twig', [
            'articles' =>$articles
        ]);
    }


    /**
     * @Route("/show/categorie/{categorie_id?}", name="article_show_categorie")
     * @IsGranted("ROLE_REDACTEUR")
     */
    public function showArticleByCategorie($categorie_id, ArticleRepository $repo, Request $request, EntityManagerInterface $manager): Response
    {
        $articles=$repo->findBy([
            "categorie"=>$categorie_id
        ]);
       
        return $this->render('article/index.html.twig', [
            'articles' =>$articles
        ]);
    }


    /**
     * @Route("/add/{id?}", name="article_add",methods={"POST","GET"})
     * @IsGranted("ROLE_REDACTEUR")
     */
    public function save($id, ArticleRepository $repo, Request $request, EntityManagerInterface $manager): Response
    {
        if (!empty($id)) {
            //Gestion du formulaire en Mode Modification
            $article=$repo->find($id);
        } else {
            //Gestion du formulaire en Mode Ajout
            $article=new Article();
        }


        //Mapper le formulaire et l'objet $article
        $form = $this->createForm(ArticleType::class, $article);

        //Recuperation $_POST['name']
        //hydratation de $categorie à partir des Données du formulaire
        $form->handleRequest($request);

        // isset($_POST['name_btn'])
        if ($form->isSubmitted() && $form->isValid()) {
            //Ajout
            $manager->persist($article);
            $manager->flush();
            return $this->redirectToRoute("article_add");
        }



        return $this->render('article/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id?}", name="article_delete",methods={"GET"})
     * @IsGranted("ROLE_REDACTEUR")
     */
    public function delete(Article $article, ArticleRepository $repo, EntityManagerInterface $manager): Response
    {
        $manager->remove($article);
        $manager->flush();
        return $this->redirectToRoute("article_show");
    }
}

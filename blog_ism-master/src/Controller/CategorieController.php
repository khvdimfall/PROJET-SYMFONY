<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategorieController extends AbstractController
{
    /**
     * @Route("/categorie/show/{id?}", name="categorie_show",methods={"POST","GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function show($id, CategorieRepository $repo, Request $request, EntityManagerInterface $manager): Response
    {
        //Request => $_GET ou $_POST
        //dd($repo->findAll());
        
        
        $categories=$repo->findAll();
        
       
        if (!empty($id)) {
            //Gestion du formulaire en Mode Modification
            $categorie=$repo->find($id);
        } else {
            //Gestion du formulaire en Mode Ajout
            $categorie=new Categorie();
        }
        

        //Mapper le formulaire et l'objet $categorie
        $form = $this->createForm(CategorieType::class, $categorie);


        //Recuperation $_POST['name']
        //hydratation de $categorie à partir des Données du formulaire
        $form->handleRequest($request);
        // isset($_POST['name_btn'])
        if ($form->isSubmitted() && $form->isValid()) {
            //Ajout
            $manager->persist($categorie);
            $manager->flush();
            return $this->redirectToRoute("categorie_show");
        }

        return $this->render('categorie/index.html.twig', [
            "categories"=>$categories,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/categorie/showArticleCategorie/{id?}", name="categorie_article",methods={"POST","GET"})
     */
    /*
    public function showArticleCategorie($id, CategorieRepository $repo): Response
    {

        //dd($repo->findAll());
        if (!empty($id)) {
            $art_of_cats=$repo->findArticleofCategorie($id);
            //dd($art_of_cat);
        }


        return $this->render('categorie/artCategorie.html.twig', [
            "art_of_cats"=>$art_of_cats
        ]);
    }
    */

    /**
     * @Route("/categorie/add/{sms?}", name="categorie_add",methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function add(): Response
    {
        //dd($repo->findAll());
        $categories=$repo->findAll();
        return $this->render('categorie/index.html.twig', [
            "categories"=>$categories
        ]);
    }

    /**
     * @Route("/categorie/update/{sms?}", name="categorie_update",methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function update(): Response
    {
        //dd($repo->findAll());
        $categories=$repo->findAll();
        return $this->render('categorie/index.html.twig', [
            "categories"=>$categories
        ]);
    }

    /**
     * @Route("/categorie/delete/{id?}", name="categorie_delete",methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete($id, CategorieRepository $repo, EntityManagerInterface $manager): Response
    {
        $categorie=$repo->find($id);
        $manager->remove($categorie);
        $manager->flush();
        return $this->redirectToRoute("categorie_show");
    }
}

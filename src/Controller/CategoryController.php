<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="app_category")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        // FONCTION QUI RECUPERE TOUT LES categoryS DE LA BDD
        $categories = $doctrine->getRepository(Category::class)->findAll();
        return $this->render('category/index.html.twig', [
            'categories' => $categories
        ]);
    }

// FONCTION D'AJOUT ET D'EDITION DE CATEGORY ------------------------------------
    /**
     * @Route("/category/add", name="add_category")
     * @Route("/category/{id}/edit", name="edit_category")
     */
    public function add(ManagerRegistry $doctrine, Category $category = null, Request $request): Response {

        if(!$category) {
            $category = new category();
        }
    

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $category = $form->getData();
            $entityManager = $doctrine->getManager();
            //prepare
            $entityManager->persist($category);
            //execute
            $entityManager->flush();

            return $this->redirectToRoute('app_category');
        }


        //vue pour afficher le formulaire
        return $this->render('category/add.html.twig', [
            //génère le formulaire visuellement
            'formAddCategory' =>$form->createView(),
            //recupere pour l'edit
            'edit' => $category->getId()
        ]);
    }


// SUPPRESSION category ----------------------------------------------------
    /**
     * @Route("category/{id}/delete", name="delete_category")
     */
    public function delete(ManagerRegistry $doctrine, category $category) {

        $entityManager = $doctrine->getManager();
        // enleve de la collection de la base de données
        $entityManager->remove($category);
        $entityManager->flush();

        return $this->redirectToRoute('app_category');
    }



// AFFICHER UNE CATEGORIE--------------------------------------------------------
    /**
     * @Route("/category/{id}", name="show_category")
     */
    public function show(Category $category): Response
    {
// FONCTION QUI RECUPERE LE category DE LA BDD PAR SON ID
        return $this->render('category/show.html.twig', [
            'category' => $category
        ]);
    }
}

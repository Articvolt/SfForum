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
    // PAGE D'ACCUEIL + LISTE CATEGORIES
    /**
     * @Route("/", name="app_category")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        // FONCTION QUI RECUPERE TOUT LES categoryS DE LA BDD
        $categories = $doctrine->getRepository(Category::class)->findAll();
        return $this->render('category/index.html.twig', [
            'categories' => $categories
        ]);
    }

// FONCTION D'AJOUT ET D'EDITION DE CATEGORIE ------------------------------------
    /**
     * @Route("/category/add", name="add_category")
     * @Route("/category/{id}/edit", name="edit_category")
     */
    public function add(ManagerRegistry $doctrine, Category $category = null, Request $request): Response {
        // Le = null indique qu'il n'est pas obligatoire de passer une valeur à cet argument lors de l'appel de la méthode.
        // Cette syntaxe est souvent utilisée pour permettre à une méthode de fonctionner à la fois comme une méthode de création et comme une méthode de modification. 
        // Si vous passez un objet Category existant à la méthode, elle fonctionnera en mode modification et mettra à jour l'objet existant. 
        // Si vous ne passez pas d'objet Category, la méthode fonctionnera en mode création et créera un nouvel objet Category.

        // création d'un nouvel objet "catégorie" 
        if(!$category) {
            $category = new category();
        }
    
        // création d'une instance du formulaire de création de catégorie
        $form = $this->createForm(CategoryType::class, $category);
        // gestion de la soumission du formulaire
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // récupère les données du formulaire
            $category = $form->getData();
            // Le gestionnaire d'entités, ou EntityManager, est un objet utilisé pour gérer les entités dans une application Doctrine. 
            // Il est généralement utilisé pour enregistrer, mettre à jour et supprimer des entiés dans la base de données.
            $entityManager = $doctrine->getManager();
            //prepare l'enregistrement de la catégorie en base de données
            $entityManager->persist($category);
            //enregistre la catégorie en base de données
            $entityManager->flush();

            //redirige l'utilisateur vers la route 'app_category'
            return $this->redirectToRoute('app_category');
        }


        // Si le formulaire n'a pas été soumis ou s'il n'est pas valide, affichez-le à nouveau à l'utilisateur
        return $this->render('category/add.html.twig', [
            //génère le formulaire visuellement
            'formAddCategory' =>$form->createView(),
            //recupere pour l'edit
            'edit' => $category->getId()
        ]);
    }


// SUPPRESSION DE CATEGORIE ----------------------------------------------------
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

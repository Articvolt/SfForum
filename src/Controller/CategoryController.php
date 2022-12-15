<?php

namespace App\Controller;

use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
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

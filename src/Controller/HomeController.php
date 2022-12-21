<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    // PAGE D'ACCUEIL ---------------------------------------------------------------

    // route vide pour quand le projet est lancé, tombe sur cette page
    /**
     * @Route("/", name="app_home")
     */
    // ajout en argument du dépôt/repository de catégorie
    public function index(CategoryRepository $catRep): Response
    {
        // utilisation de la requête findAll pour lister les catégories
        $categories = $catRep->findAll();
        return $this->render('home/index.html.twig', [
            'categories' => $categories,
        ]);
    }
}

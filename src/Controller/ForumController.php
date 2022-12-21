<?php

namespace App\Controller;

use App\Entity\Topic;
use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ForumController extends AbstractController
{
//================================ TOPIC ==================================================

// AFFICHER LES SUJETS D'UNE CATEGORIE-----------------------------------------------------
   
    /**
     * @Route("/forum/topic/{id}", name="show_topic")
     */

    public function showTopic(Category $category): Response
    {
        return $this->render('forum/topics.html.twig', [
            'category' => $category
        ]);
    }

//================================ POST ===================================================


// AFFICHER UN SUJET AVEC SES MESSAGES-----------------------------------------------------
    
    /**
    * @Route("/forum/post/{id}", name="show_post")
    */
    
    public function showPost(Topic $topic): Response
    {
        return $this->render('forum/posts.html.twig', [
            'topic' => $topic
        ]);
    }
}

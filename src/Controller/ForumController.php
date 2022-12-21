<?php

namespace App\Controller;

use App\Entity\Topic;
use App\Entity\Category;
use Knp\Component\Pager\PaginatorInterface;
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

    public function showTopic(Category $category, PaginatorInterface $paginator, Request $request): Response
    {
        $topics = $paginator->paginate(
            $category->getTopics(),
            $request->query->getInt('page', 1), 4
        );

        return $this->render('forum/topics.html.twig', [
            'category' => $category,
            'topics' => $topics
        ]);
    }

//================================ POST ===================================================


// AFFICHER UN SUJET AVEC SES MESSAGES-----------------------------------------------------
    
    /**
    * @Route("/forum/post/{id}", name="show_post")
    */
    
    public function showPost(Topic $topic, PaginatorInterface $paginator, Request $request): Response
    {
        $posts = $paginator->paginate(
            $topic->getPosts(), 
            $request->query->getInt('page', 1), 4
        );

        return $this->render('forum/posts.html.twig', [
            'topic' => $topic,
            'posts' => $posts,
        ]);
    }
}

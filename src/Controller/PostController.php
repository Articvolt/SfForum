<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    /**
     * @Route("/post", name="app_post")
     */
    public function index(): Response
    {
        return $this->render('category/topics.html.twig', []);
    }

// SUPPRESSION MESSAGE ----------------------------------------------------
    /**
     * @Route("post/{id}/delete", name="delete_post")
     */
    public function delete(ManagerRegistry $doctrine, Post $post) {

        $entityManager = $doctrine->getManager();
        // enleve de la collection de la base de données
        $entityManager->remove($post);
        $entityManager->flush();

        $id = $post->getTopic()->getId();
        return $this->redirectToRoute('category/show', ['id' => $id]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Topic;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
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

// AJOUTER UN MESSAGE
    /**
     * @Route("/post/{id}/add", name="add_post")
     */
    public function add(Topic $topic, Request $request, Post $post, ManagerRegistry $doctrine)
    {
        //appel du formulaire
        $postForm = $request->request;
        // récuperation de l'utilisateur
        $user_id = $this->getUser();
        // filtre le message dans le formulaire
        $message = $postForm->filter('post');

        
        $entityManager = $doctrine->getManager();
        // default -> to be replaced with connected user
        $auteur = $doctrine->getRepository(User::class)->findOneBy(['id' => $user_id]);
        
        // Donne la date actuelle
        // $date = new DateTime();

        // ajout d'un nouveau Post
        $postForm = new Post();
        $postForm->setMessage($message);
        $postForm->setDatePost(new DateTime());
        $postForm->setUser($auteur);
        $postForm->setTopic($topic);

        // Ajout dans la base de données
        $entityManager->persist($postForm);
        $entityManager->flush();

        $id = $post->getTopic()->getId();
        return $this->redirectToRoute('show_topic',['id' => $id]);
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
        return $this->redirectToRoute('show_topic', ['id' => $id]);
    }
}

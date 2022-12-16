<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Topic;
use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TopicController extends AbstractController
{
    /**
     * @Route("/topic", name="app_topic")
     */
    public function index(ManagerRegistry $doctrine, PaginatorInterface $paginator, Request $request): Response
    {
        // FONCTION QUI RECUPERE TOUT LES topicS DE LA BDD
        $topics = $doctrine->getRepository(Topic::class)->findAll();
        
        $topics = $paginator->paginate(
            $topics,
            $request->query->getInt('page', 1), //nombre de pages
            2 // limite par page
        );

        return $this->render('topic/index.html.twig', [
            'topics' => $topics
        ]);
    }

// AJOUTER UN TOPIC + PREMIER POST

    // /**
    // * @Route("/categorie/{id}/addTopic", name"add_topic")
    // */
    // public function addTopicPost(ManagerRegistry  $doctrine, Category $category, Request $request): Response
    // {
    //     $topic = new Topic;
        
    //     $form = $this->createForm(TopicType::class);
    //     $form->handleRequest($request);

    //     if($form->isSubmitted() && $form->isValid()){
            
    //         $entityManager = $doctrine->getManager();

    //         $nameTopic = $form->get("titreTopic")->getData();
    //         $texteFirstPost = $form->get("texteFirstPost")->getData();

    //         $auteur = $this->getUser();
    //         $date = new DateTime();

    //         // création d'un nouveau sujet
    //         $topic = new Topic;
    //         $topic->setnameTopic($nameTopic);
    //         $topic->setUser($auteur);
    //         $topic->setDateTopic($date);
    //         $topic->setCategory($category);
            

    //         // ajout du topic dans la base de données
    //         $entityManager->persist($topic); //équivalent de prepare()
    //         $entityManager->flush(); //équivalent de execute()
            
    //         // création d'un nouveau message
    //         $post = new Post;
    //         $post->setNamePost($texteFirstPost);
    //         $post->setUser($auteur);
    //         $post->setDatePost($date);
    //         $post->setTopic($topic);
            
    //         $entityManager->persist($post); 
    //         $entityManager->flush();

    //         return $this->redirectToRoute('show_topic', ['id' => $topic->getId()]);
    //     }

    //     //Vue pour afficher le formulaire d'ajout
    //     return $this->render('topic/add.html.twig', [
    //         'formAddTopic' =>$form->createView(),
    //         'category' => $category
    //     ]);

    // }   




// AFFICHER UN SUJET--------------------------------------------------------
    /**
    * @Route("/topic/{id}", name="show_topic")
    */
    public function show(Topic $topic): Response
    {
// FONCTION QUI RECUPERE LE SUJET DE LA BDD PAR SON ID
        return $this->render('category/topics.html.twig', [
            'topic' => $topic
        ]);
    }
}

// https://www.youtube.com/watch?v=A94egHxWaHo : pagination tutoriel video

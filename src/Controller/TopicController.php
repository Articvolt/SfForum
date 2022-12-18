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

    /**
    * @Route("/categorie/{id}/addTopic", name"add_topic")
    */
    public function addTopicPost(ManagerRegistry  $doctrine, Category $category, Request $request): Response
    {
        $form = $this->createForm(TopicType::class);
        // Récupération des données du formulaire
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            // Récupération des données du formulaire
            $topic = $form->getData();
            $entityManager = $doctrine->getManager();
            $auteur = $this->getUser();
            
            $topic = $entityManager->getRepository(Topic::class)->find($topic->getId());
            // $auteur = $this->getUser();
             // Vérification si c'est une nouvelle création ou une mise à jour
            if ($topic->getId()) {
                // Mise à jour des champs de l'objet
                $topic->setNameTopic($topic['NameTopic']);
                $topic->setCategory($topic['category']);
            } else {
                // C'est une nouvelle création, on crée un nouvel objet
                $topic = new Topic();
                $topic->setNameTopic($topic['NameTopic']);
                $topic->setCategory($topic['category']);
                $topic->setUser($auteur);
                $topic->setDateTopic(new \DateTime());
            }

            // Persistence de l'objet
            
            $entityManager->persist($topic);
            $entityManager->flush();


            return $this->redirectToRoute('show_topic', ['id' => $topic->getId()]);
        }

        //Vue pour afficher le formulaire d'ajout
        return $this->render('topic/add.html.twig', [
            'formAddTopic' =>$form->createView(),
            'category' => $category,
        ]);

    }   




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

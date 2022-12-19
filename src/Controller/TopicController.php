<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Topic;
use App\Form\TopicType;
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
     * @Route("/topic/add", name="add_topic")
     * @Route("/topic/{id}/edit", name="edit_topic")
     */
    public function add(ManagerRegistry $doctrine, Topic $topic = null,Category $category, Request $request) : Response 
    {

        if(!$topic) {
            $topic = new Topic();
        }

        $form = $this->createForm(TopicType::class, $topic);      
        $form->handleRequest($request);
        //si la données est "sanitize" on l'envoi
        if ($form->isSubmitted() && $form->isValid()) { 
            $entityManager = $doctrine->getManager();
            
            $topic = $form->getData();

            // date actuelle
            $now = new \DateTime();
            // ajoute la date actuelle
            $topic->setDateTopic($now);
            $topic->setCategory()->getId($category);

            //prepare
            $entityManager->persist($topic);
            //insert into
            $entityManager->flush();

            return $this->redirectToRoute('app_topic');
        }
                   
        //vue pour afficher le formulaire d'ajout ou d'edition
        //vue pour afficher le formulaire d'ajout
        return $this->render('topic/add.html.twig', [
            'formAddTopic' => $form->createView(),
            'edit' =>$topic->getId(),  
                               
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

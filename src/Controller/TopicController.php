<?php

namespace App\Controller;

use App\Entity\Topic;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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

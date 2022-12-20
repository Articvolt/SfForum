<?php

namespace App\Controller;

use App\Entity\Topic;
use App\Form\TopicType;
use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
     * @Route("/topic/add/{idCategory}", name="add_topic")
     * @Route("/topic/{idTopic}/edit", name="edit_topic")
     * @ParamConverter("topic", options = {"mapping": {"idTopic": "id"}})
     * @ParamConverter("category", options={"mapping": {"idCategory": "id"}})
     */
    public function add(ManagerRegistry $doctrine, Topic $topic = null, Security $security, Category $category, Request $request) : Response 
    {
        if(!$topic) {
            $topic = new Topic();
        }

        $form = $this->createForm(TopicType::class, $topic);      
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { 
            
            // On récupèrer l'utilisateur en session
            $user = $security->getUser();
            $topic->setUser($user);
            // ajoute la date actuelle
            $topic->setDateTopic(new \DateTime('now'));
            // ajoute l'id de la catégorie
            $topic->setCategory($category);
            
            // récupère les données du formulaire
            $topic = $form->getData();
            
            // utilisé pour gérer les entités dans une application Doctrine.
            $entityManager = $doctrine->getManager();
            //prepare l'enregistrement du sujet en base de données
            $entityManager->persist($topic);
            //enregistre le sujet en base de données
            $entityManager->flush();
            //redirige l'utilisateur vers la route 'app_topic'
            return $this->redirectToRoute('show_topic', ['idTopic'=> 'id']);
        }
                   
        //vue pour afficher le formulaire d'ajout ou d'edition
        //vue pour afficher le formulaire d'ajout
        return $this->render('topic/add.html.twig', [
            'formAddTopic' => $form->createView(),
            'edit' =>$topic->getId(),  
                               
        ]);
    }

// SUPPRESSION TOPIC ----------------------------------------------------
    /**
     * @Route("topic/{idTopic}/delete", name="delete_topic")
     * @ParamConverter("topic", options = {"mapping": {"idTopic": "id"}})
     */
    public function delete(ManagerRegistry $doctrine, topic $topic) {
        $idCategory = $topic->getCategory()->getId();
        // dd($idCategory);
        $entityManager = $doctrine->getManager();
        // enleve de la collection de la base de données
        $entityManager->remove($topic);
        $entityManager->flush();

        return $this->redirectToRoute('show_category', ['id' => $idCategory]);
    }
// AFFICHER UN SUJET--------------------------------------------------------
    /**
    * @Route("/topic/{idTopic}", name="show_topic")
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

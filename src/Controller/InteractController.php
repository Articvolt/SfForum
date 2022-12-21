<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Topic;
use App\Form\PostType;
use App\Form\TopicType;
use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class InteractController extends AbstractController
{

//==================================== CATEGORY ================================================== 
    
// FONCTION D'AJOUT ET D'EDITION DE CATEGORIE ------------------------------------
    /**
     * @Route("/category/add", name="add_category")
     * @Route("/category/{id}/edit", name="edit_category")
     */
    public function addCategory(ManagerRegistry $doctrine, Category $category = null, Request $request): Response {
        // Le = null indique qu'il n'est pas obligatoire de passer une valeur à cet argument lors de l'appel de la méthode.
        // Cette syntaxe est souvent utilisée pour permettre à une méthode de fonctionner à la fois comme une méthode de création et comme une méthode de modification. 
        // Si vous passez un objet Category existant à la méthode, elle fonctionnera en mode modification et mettra à jour l'objet existant. 
        // Si vous ne passez pas d'objet Category, la méthode fonctionnera en mode création et créera un nouvel objet Category.

        // création d'un nouvel objet "catégorie" 
        if(!$category) {
            $category = new category();
        }
    
        // création d'une instance du formulaire de création de catégorie
        $form = $this->createForm(CategoryType::class, $category);
        // gestion de la soumission du formulaire
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // récupère les données du formulaire
            $category = $form->getData();
            // Le gestionnaire d'entités, ou EntityManager, est un objet utilisé pour gérer les entités dans une application Doctrine. 
            // Il est généralement utilisé pour enregistrer, mettre à jour et supprimer des entiés dans la base de données.
            $entityManager = $doctrine->getManager();
            //prepare l'enregistrement de la catégorie en base de données
            $entityManager->persist($category);
            //enregistre la catégorie en base de données
            $entityManager->flush();

            //redirige l'utilisateur vers la route 'app_category'
            return $this->redirectToRoute('app_home');
        }


        // Si le formulaire n'a pas été soumis ou s'il n'est pas valide, affichez-le à nouveau à l'utilisateur
        return $this->render('interact/addCategory.html.twig', [
            //génère le formulaire visuellement
            'formAddCategory' =>$form->createView(),
            //recupere pour l'edit
            'edit' => $category->getId()
        ]);
    }


// SUPPRESSION DE CATEGORIE ----------------------------------------------------

    /**
    * @Route("home/{id}/delete", name="delete_category")
    */

    // ManagerRegistery -> gère les connexions entre les bases de données et les EntityManager
    public function deleteCategory(ManagerRegistry $doctrine, category $category) {

        $entityManager = $doctrine->getManager();
        // enleve de la collection de la base de données
        $entityManager->remove($category);
        $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }

//============================================= TOPIC =========================================

// AJOUT ET EDITION D'UN SUJET + PREMIER MESSAGE

    /**
    * @Route("forum/{idCategory}/topic/add", name="add_topic")
    * @Route("forum/{idCategory}/topic/{id}/edit", name="edit_topic")
    * @ParamConverter("category", options={"mapping": {"idCategory": "id"}})
    */
    public function addTopic(ManagerRegistry $doctrine, Category $category, Request $request): Response {
        
        $topic = new Topic;
        $post = new Post;

        // création du formulaire
        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);
        
        $entityManager = $doctrine->getManager();

        
        if ($form->isSubmitted() && $form->isValid()) {
            
            // PARTIE TOPIC 
            
            // récupère les données du formulaire
            $topic = $form->getData();

            // ajoute l'utilisateur actuel
            $topic->setUser($this->getUser());
            // ajoute la date
            $topic->setDateTopic(new \DateTime('now'));
            // ajoute le sujet à la categorie
            $category->addTopic($topic);
            // préparation pour l'enregistrement du sujet
            $entityManager->persist($topic);
            
            // PARTIE POST
            
            // ajoute le message
            $post->setMessage($form->get('firstMessage')->getData());
            // ajoute l'utilisateur
            $post->setUser($this->getUser());
            // ajoute le sujet au message
            $post->setTopic($topic);
            // ajoute la date
            $post->setDatePost(new \DateTime('now'));
            
            // préparation pour l'enregistrement du message
            $entityManager->persist($post);

            // envoi l'enregistrement de topic et post
            $entityManager->flush();
            
            $idCategory=$category->getId();
            return $this->redirectToRoute("show_topic", ["id" => $idCategory]);
        }

        // vue pour afficher le formulaire d'ajout
        return $this->render('interact/addTopic.html.twig', [
            // création d'une variable qui fait passer le formulaire qui a était créé visuellement
            'formAddTopic' => $form->createView(),
            'topic' => $topic,
            'edit' => $topic->getId()
        ]);
    }

// SUPPRESSION D'UN SUJET ----------------------------------------------------
    /**
     * @Route("forum/{id}/delete", name="delete_topic")
     */
    public function deleteTopic(ManagerRegistry $doctrine, Topic $topic) {
        $idCategory = $topic->getCategory()->getId();
        // dd($idCategory);
        $entityManager = $doctrine->getManager();
        // enleve de la collection de la base de données
        $entityManager->remove($topic);
        $entityManager->flush();

        return $this->redirectToRoute('show_topic', ['id' => $idCategory]);
    }

//======================================= POST ===============================================

// AJOUT ET EDITION D'UN MESSAGE

/**
     * @Route("forum/{idTopic}/post/add", name="add_post")
     * @Route("forum/{idTopic}/post/{id}/edit", name="edit_post")
     * @ParamConverter("topic", options={"mapping": {"idTopic": "id"}})
     */
    public function addPost(ManagerRegistry $doctrine,Topic $topic, Post $post = null, Request $request): Response {

        
        $post= new Post();

        // construit un formulaire à partir d'un builder (PostType)
        $form = $this->createForm(PostType::class, $post);
        // récupère les données de l'objet pour les envoyer dans le formulaire
        $form->handleRequest($request);

        // si le formulaire est soumis et que les filtres ont été validés (fonctions natives de symfony)
        if($form->isSubmitted() && $form->isValid()) {

            // recupère depuis doctrine, le manager qui est initialisé (où se situe le persist et le flush)
            $post = $form->getData();
            $entityManager = $doctrine->getManager();

            // récupère le User
            $post->setUser($this->getUser());
            // récupère la date
            $post->setDatePost(new \DateTime('now'));
            // ajout du topic
            $topic->addPost($post);

            // équivalent tu prepare();
            $entityManager->persist($post);
            // équivalent du execute() -> insert into
            $entityManager->flush();

            $idTopic=$topic->getId();
            return $this->redirectToRoute('show_post',['id' => $idTopic]);
        }

        // vue pour afficher le formulaire d'ajout
        return $this->render('interact/addPost.html.twig', [
            // création d'une variable qui fait passer le formulaire qui a était créé visuellement
            'formAddPost' => $form->createView(),
            'edit' => $post->getId()
        ]);
    }

// SUPPRESSION MESSAGE ----------------------------------------------------
    /**
     * @Route("forum/post/{id}/delete", name="delete_post")
     */
    public function deletePost(ManagerRegistry $doctrine, Post $post) {

        $entityManager = $doctrine->getManager();
        // enleve de la collection de la base de données
        $entityManager->remove($post);
        $entityManager->flush();

        $id = $post->getTopic()->getId();
        return $this->redirectToRoute('show_post', ['id' => $id]);
    }
}

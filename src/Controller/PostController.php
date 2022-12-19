<?php

namespace App\Controller;

use DateTime;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Topic;
use App\Form\PostType;
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
    // /**
    //  * @Route("/post/add", name="add_post")
    //  */
    public function add(ManagerRegistry $doctrine, Post $post = null, Request $request): Response {

        if(!$post) {
            $post= new Post();
        }

        // construit un formulaire à partir d'un builder (PostType)
        $form = $this->createForm(PostType::class, $post);
        // récupère les données de l'objet pour les envoyer dans le formulaire
        $form->handleRequest($request);

        // si le formulaire est soumis et que les filtes ont été validés (fonctions natives de symfony)
        if($form->isSubmitted() && $form->isValid()) {

            $post = $form->getData();
            // recupère depuis doctrine, le manager qui est initialisé (où se situe le persist et le flush)
            $entityManager = $doctrine->getManager();
            // équivalent tu prepare();
            $entityManager->persist($post);
            // équivalent du execute() -> insert into
            $entityManager->flush();

            return $this->redirectToRoute('app_post');
        }

        // vue pour afficher le formulaire d'ajout
        return $this->render('Post/add.html.twig', [
            // création d'une variable qui fait passer le formulaire qui a était créé visuellement
            'formAddPost' => $form->createView(),
            'edit' => $post->getId()
        ]);
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

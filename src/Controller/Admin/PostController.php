<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/post', name: 'admin_post_')]
class PostController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(PostRepository $postRepository): Response
    {
                $posts = $postRepository->findAll();

        return $this->render('admin/post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route("/add", name:"add")]
    public function addPost(Request $request, ManagerRegistry $doctrine): Response
    {
        // dd($request);
        $posts = new Post();
        // dd($posts);
        $form = $this->createForm(PostType::class, $posts,  array('labelButton' => 'ajouter'));
        // dd($form);
        // dd($form->createView());

        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $em = $this->getDoctrine()->getManager(); ancienne depreciée
            $em = $doctrine->getManager();
            $em->persist($posts);
            $em->flush();
            return $this->redirectToRoute('admin_post_index');
        }
        
        
        return $this->render('admin/post/add.html.twig', [
            'title' => 'Ajout d\'un article',
            'form' => $form->createView(),
        ]);
    }

    #[Route("/update/{id}", name:"update")]
    public function updateposts(Post $posts, Request $request, ManagerRegistry $doctrine): Response
    {
        // dd($request);
        //$posts = new Post(); Pas besoin car recuperer par param converter
        // dd($posts);
        $form = $this->createForm(PostType::class, $posts,  array('labelButton' => 'modifier'));
        // dd($form);
        // dd($form->createView());

        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $em = $this->getDoctrine()->getManager(); ancienne depreciée
            $em = $doctrine->getManager();
            $em->persist($posts);
            $em->flush();
            return $this->redirectToRoute('admin_post_index');
        }
        
        
        return $this->render('admin/post/add.html.twig', [
            'form' => $form->createView(),
            'title' => 'Modification d\'un article',

        ]);
    }

    #[Route("/delete/{id}", name: "delete")]
    public function delete(Post $posts, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $em->remove($posts);
        $em->flush();
        $this->addFlash('success', 'Article supprimé !');
        return $this->redirectToRoute('admin_post_index');
    }

}

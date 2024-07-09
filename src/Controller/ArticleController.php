<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;

#[Route(path: '/articles', name: 'articles_')] // Préfixe de route pour tout le controlleur
class ArticleController extends AbstractController
{
    #[Route(path: '/', name: 'list')]
    public function list(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/list.html.twig', [
            'articles' => $articleRepository->findAll()
        ]);
    }

    #[Route(path: '/show/{id}', name: 'show')]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article
        ]);
    }

    #[Route(path: '/edit/{id}', name: 'edit')] // {id} = paramètre dynamique de l'URL, pou identifier l'article à éditer
    #[Route(path: '/create', name: 'create')]
    public function edit(Request $request, EntityManagerInterface $en, ?Article $article = null): Response
    {
        $isCreate = false;
        if (!$article) {
            $isCreate = true;
            $article = new Article();
        }

        $form = $this->createForm(ArticleType::class, $article);
        /* METHODE d'imbrication de formulaire (3/7) OLD
        $form->add('submit', SubmitType::class); // On gère l'envoie via le controlleur au lieu de le mettre dans le formulaire */

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $article = $form->getData();
            $article->setStatus('DRAFT');
            $en->persist($article); // prépare la sauvegarde de notre article
            $en->flush(); // flush les infos

            $this->addFlash('success', $isCreate ? 'l\'article a été créé' : 'L\'article a été modifié');

            return $this->redirectToRoute('articles_list');
        }
        return $this->render('article/edit.html.twig', [
            'form' => $form
        ]);
    }
    #[Route(path: '/delete/{id}', name: 'delete')]
    public function delete(EntityManagerInterface $en, Article $article): RedirectResponse
    {
        $en->remove($article);
        $en->flush();
        $this->addFlash('success', 'l\'article a été supprimé');
        return $this->redirectToRoute('articles_list');
    }
}
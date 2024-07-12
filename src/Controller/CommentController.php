<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Comment;
use App\Entity\Article;
use App\Form\CommentType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

#[Route(path: '/comments', name: 'comments_')] // Préfixe de route pour tout le controlleur
class CommentController extends AbstractController
{
    #[Route(path: '/create{article}', name: 'create')]
    public function edit(Request $request, EntityManagerInterface $en, Article $article): RedirectResponse
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setPublishedAt(new \DateTimeImmutable());
            $en->persist($comment); // prépare la sauvegarde de notre article
            $en->flush(); // flush les infos

            $this->addFlash('success', 'Commentaire envoyé');
        }
        return $this->redirectToRoute('articles_show', ['id' => $article->getId()]);
    }
}
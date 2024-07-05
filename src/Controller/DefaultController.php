<?php
namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'index')] // Si requete = /, name = index
    public function index(): Response // return = contenu texte || tableau clé/valeur Json || template twig avec $this->render()
    {
        return new Response ('reponse ok!'); // réponse contenu texte
    }
}
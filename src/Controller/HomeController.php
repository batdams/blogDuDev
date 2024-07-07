<?php
namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; //contient notamment la méthode render pour générer un rendu HTML ac templates Twig

class HomeController extends AbstractController
{
    #[Route('/')] // Attribut PHP (V8+), utilisé pour la création de routes avec Sf
    public function number() : Response
    {
        $number = rand(0, 100);
        return $this->render('base.html.twig', [
                  'number' => $number,
        ]);   
    }
}
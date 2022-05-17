<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



/*
L'annotation de route mise au dessus de la classe défini
un préfixe pour les urls des routes définis à l'intérieur 
de la classe 

*/
#[Route('/routing')]
class RoutingController extends AbstractController
{
    /**
     * ici la route pour accéder à cette fonction sera donc
     * /routing  ou /routing/
     */
    #[Route('/', name: 'app_routing')]
    public function index(): Response
    {
        return $this->render('routing/index.html.twig', [
            'controller_name' => 'RoutingController',
        ]);
    }

    /*
    ici {qui} est une partie variable de l'url.
    l'url /routing/bonjour/cesaire donnera {qui} vaut cesaire
    */
    #[Route('/bonjour/{qui}', name:'bonjour')]
    public function bonjour($qui)
    {
    

    
    return $this->render('index/hello.html.twig', [
      'prenom'=>$qui
    ]);
    }

    /*
    defaults permet de donner une valeur par defaut aux parties variables
    de l'url.
    Si je tape routing/salut/
    {qui} vaudra 'à toi'
    */
    #[Route('/salut/{qui}', defaults:['qui'=>'à toi'] ,name:'salut')]
    public function salut($qui)
    {
    

    
    return $this->render('index/hello.html.twig', [
      'prenom'=>$qui
    ]);
    }
















}

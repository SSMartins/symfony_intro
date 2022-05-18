<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
    l'url /routing/bonjour/susana donnera {qui} vaut susana
    */
    #[Route('/bonjour/{qui}', name:'bonjour')]
    public function bonjour($qui, SessionInterface $session)
    {
    // cf exercice HttpControleur méthode formulaire()
    // Nous devons récupérer les données enregistrées en session
    //et les afficher dans le template hello.html.twig
    // Pour récupérer les valeurs il nous faut appeler
    //la session de symfony contenue dans l'objet SessionInterface
    // $session
    // j'utilise la méthode get('') $session pour récupérer les 
    //valeurs et les affectes à des variables
      $email=$session->get('email');
      $message=$session->get('message');
    
      //Je dois supprimer de la session les enregistrements
      //j'utilise la méthode clear() de l'objet $session
      $session->clear();

      // je renvoi dans mon template les variables chargées
      //dans le tableau de données de la méthode render
    return $this->render('index/hello.html.twig', [
      'prenom'=>$qui,
      'email'=>$email,
      'message'=>$message
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

    #[Route('/coucou/{prenom}-{nom}', defaults:['nom'=>''] , name:'coucou')]
    public function coucou($prenom, $nom)
    {
      $nomComplet=$prenom.' '.$nom;
    
    return $this->render('index/hello.html.twig', [
      'prenom'=>$nomComplet
    ]);
    }

    // REGEX - expressions regulier par exemple pour id, mot de passe
    //On peut spécifier le type de donnée attendue en partie variable grace à requirements qui attend, en passage d'argument des expressions régulières ice \g+ pour forcer sur un entier

    #[Route('/utilisateur/modif/{id}', name:'utilisateur', requirements:['id'=>'\d+'])] 
    public function utilisateur($id) //ici on enject nous depandance#}
    {
    
    
    return $this->render('routing/utilisateur.html.twig', [
          'id'=>$id
    ]);
    }
















}

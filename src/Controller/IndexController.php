<?php

namespace App\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route; //gere les routes dans l'url

class IndexController extends AbstractController
{
    //Decorateur de class #[Route .. , en Angular c'est @import
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('index/index.html.twig', [ //envoyer la veu
            'controller_name' => 'IndexController',  //tableau qui permettre envoyer les donnes pour utiliser dans la vue
        ]);
    }
    //on code au centre du controller
    #[Route('/templating', name:'templating')]
    public function templating()
    {
    
    
    return $this->render('index/templating.html.twig', [
        'demain'=> new DateTime('+1day')
    ]);
    }


}

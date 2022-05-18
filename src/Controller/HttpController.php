<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class HttpController extends AbstractController
{
    #[Route('/http', name: 'app_http')]
    public function index(): Response
    {
        return $this->render('http/index.html.twig', [
            'controller_name' => 'HttpController',
        ]);
    }

    #[Route('/requete', name:'requete')]
    public function requete(Request $request )
    {

    // on utilise query pour methode GET et request pour method POST
        // http://127.0.0.1:8000/requete?prenom=susans&nom=martins
        // GET transite les informations apartir d'URL (c'est le ? dans url)

        // var_dump() en symfony:
        dump($_GET);

        // On peut utiliser un objet Request grace à notre injection danc de dépandence (class ou variable applées en argumpent de la function)
        // Cette objet Request récupère la majeur partie de données de nos sperglobales ($_GET, $_POST, $_cookies)
        // $requeste->query() contient une surcouche à $_GET 

        // sa méthode all() retourne tout le contenu de $_GET
        dump($request->query->all());

        // $_GET['prenom']
        echo $request->query->get('prenom');

        // undefined index :  surnom
        //echo $_GET['surnom'];

        // request->query() ne génère pas d'erreur si l'index n'existe pas
        echo $request->query->get('surnom');

        // On peut donner une valeur par defaut en l'absence d'index
        echo $request->query->get('surnom',' John Doe');

        //isset($_GET['surnom'])
        dump($request->query->has('surnom'));

        echo '<br>'.$request->getMethod();//ou GET ou POST

        //si la page été applée en POST 
        if($request->isMethod('POST')){

            //$request->request contient un objet surcouche de $_POST
            dump($request->request->all());

            //$request->request contient mes mêmes méthode que $request->query
            echo '<br>'.$request->request->get('prenom');
        }
    
    return $this->render('http/requete.html.twig', [
    
    ]);
    }

    /*
     On inject en dépendance SessionInterface $session pour accéder à la session dans une méthode du controller*

     se meilleur passer par SessionInterface que pour $_Session parce que on symfony  $_SESSION pose des erreurs
     */
    #[Route('/session', name:'session')]
    public function session(SessionInterface $session)
    {
        $session->set('prenom', 'susana');
        $session->set('nom', 'martins');
    
        // on accède directement aux éléments enregistrés par $session sur l'index
        // _sf2_attributes de $_SESSION
        // $_SESSION['_sf2_attributes'];
        //ou bien par:
        dump($session->all());

        // pour accéder à un élément de la session
        $session->get('prenom');

        //pour supprimer un élément de la session
        $session->remove('nom');

        dump($session->all());

        //pour vider la session
        $session->clear();

        dump($session->all());
    
    return $this->render('http/requete.html.twig', [
    
    ]);
    }





    /**
     * Une méthode de contrôleur doit nécessairement retourner
     * un objet instance de la classe Symfony\Component\HttpFoundation\Response
     *
     * @Route("/reponse")
     */
    public function reponse(Request $request)
    {
        // http://localhost:8000/reponse?type=text
        if ($request->query->get('type') == 'text') {
            $response = new Response('Contenu en texte brut');

            return $response;
        // http://localhost:8000/reponse?type=json
        } elseif ($request->query->get('type') == 'json') {
            $response = [
                'nom' => 'Marx',
                'prenom' => 'Groucho'
            ];

            // return new Response(json_encode($response));

            // encode le tableau $response en json
            // et retourne une réponse avec l'entête HTTP
            // Content-Type: application/json au lieu de text/html
            return new JsonResponse($response);
        // http://localhost:8000/reponse?found=no
        } elseif ($request->query->get('found') == 'no') {
            // pour retourner une 404, on jette cette exception
            throw new NotFoundHttpException();
        // http://localhost:8000/reponse?redirect=index
        } elseif ($request->query->get('redirect') == 'index') {
            // redirection vers une page en passant le nom de sa route
            return $this->redirectToRoute('app_index');
        // http://localhost:8000/reponse?redirect=bonjour
        } elseif ($request->query->get('redirect') == 'bonjour') {
            // redirection vers une route qui contient un partie variable
            return $this->redirectToRoute(
                'bonjour',
                [
                    'qui' => 'le monde'
                ]
            );
        }

        // retourne un objet Response dont le contenu est
        // le HTML construit par le template
        return $this->render('http/reponse.html.twig');
    }

    /*
     * Faire une page avec un formulaire en post avec :
     * - email (text)
     * - message (textarea)
     *
     * Si le formulaire est envoyé, vérifier que les deux champs sont remplis
     * Si non, afficher un message d'erreur
     * Si oui, enregistrer les valeurs en session et rediriger vers
     * une nouvelle page qui les affiche et vide la session
     * Dans cette page, si la session est vide, on redirige vers le formulaire
     */



        //pour accéder à la page de cette méthose je dois saisir
        //http:127.0.0.1.800/formulaire
        //j'ai besoin de la session donc je dois injecter en dépendance SessionInterface $session
        //J'ai besoin de récupérer des données de formuliare en post, je dois donc injecter en dépendance Request $request
     #[Route('/formulaire', name:'formulaire')]
     public function formulaire(SessionInterface $session, Request $request)
     {
         // je verifie si le formulaire a été soumis
         if(!empty($_POST)){
             //allor je charge dans des variables le contenue de mes champs de formuliare ayant pour name email et message 
             //$request->request->get('name')=$_POST['name']
         $email=$request->request->get('email');
         $message=$request->request->get('message');
        
            // on verifie que les 2 champs du formulaire ne soient pas vide (strlen() ou !empty())
            if(!empty($email) && !empty($message)){
                // j'affecte en session un nouvel enregistrement grace à la méthode set() qui attend en premier argument le nom de l'enregistrement puis en second la valeur de l'enregistrement
                $session->set('email', $email);
                $session->set('message',$message);

                //je redirige vers la page hello.html.twig
                //donc la méthode bonjour(), dans le RoutingController, 
                //renvois dans le render() sur ce template et qui a pour name bonjour
                return $this->redirectToRoute(
                    'bonjour', 
                    [
                        'qui' => 'monde',
                    ]
                ); 
                //ici he ne suis plus dans la méthode formulaire mais dans la méthode bonjour();
                
            }
            else{
                echo 'Tous les champ devois être remplis !!';
            }

        }
     
     
     return $this->render('http/formulaire.html.twig', [
     
     ]);
     }


















}

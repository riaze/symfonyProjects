<?php
/**
 * Created by PhpStorm.
 * User: stagiaire
 * Date: 03/12/2018
 * Time: 11:27
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    public function home():Response
    {

        $url = $this->generateUrl('app_project_show',['id'=>155]);
        //var_dump($url);
        //die('debug generate url');
        /*return new Response('<html><body><h1>liste de Projets</h1></body></html>');*/
        return $this->render('index.html.twig', ['projects' =>[]]);


    }

    /**
     * @return Response
     * @Route("/projet/{id}", requirements={"id"="\d+"})
     */
    public function show(int $id){
        return new Response("<html><body><h1>details de projets: $id</h1></body></html>");
    }

    /**
     * @param SessionInterface $session
     * @return Response
     * @Route("/projet/creation")
     */
    public function create(SessionInterface $session): Response
    {
        $session->set('message', 'le projets a bien été ajouté');
        return $this->redirectToRoute('index');
       /* var_dump($session);
        die('debug session');*/

    }

    /**
     * @param SessionInterface $session
     * @return Response
     * @Route("/recup/session")
     */
    public function  recuperSession(SessionInterface $session) : Response
    {
        $message = $session->get('message');
        return new Response('message : '. $message);
    }

    /**
     * @Route("/flash/create")
     */
    public function createFlash(){
        $this->addFlash('success', 'projet bien ajoute');
        /*return $this->redirectToRoute('app_project_recupflash');*/
        return $this->redirectToRoute('index');

    }

    /**
     * @param SessionInterface $session
     * @return Response
     * @Route("/flash/recup")
     */
    public function  recupFlash(Session $session){

        $message = $session->getFlashBag()->get('success');

        return new Response('Message flash:'.implode('<br>', $message));
    }
}



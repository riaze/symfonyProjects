<?php
/**
 * Created by PhpStorm.
 * User: stagiaire
 * Date: 03/12/2018
 * Time: 11:27
 */

namespace App\Controller;


use App\Entity\Project;
use App\Form\ProjectType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    public function home():Response
    {

        /*$url = $this->generateUrl('app_project_show',['id'=>155]);*/
        $url = $this->generateUrl('app_project_show',['slug'=> 'eeee-eeee']);

        //var_dump($url);
        //die('debug generate url');
        /*return new Response('<html><body><h1>liste de Projets</h1></body></html>');*/

        // Récuperation du Répository des project

        $porjectRepository = $this->getDoctrine()->getRepository(Project::class);
        $projects = $porjectRepository->findAll();
        $projects = $porjectRepository->findBy(['isPublished' => true]);
        return $this->render('index.html.twig', ['projects' =>$projects]);


    }

    /**
     * @return Response
     * @Route("/oldprojet/{slug}")
     * @return Response
     */
    ///requirements={"id"="\d+"}
    public function Oldshow(string $slug):Response{

        //Récupération du Repository

        $projetctRepo = $this->getDoctrine()->getRepository(Project::class);

        //Récupération du projet par rapport à son string

        $project = $projetctRepo->findOneBy(['slug' => $slug]);

        if(is_null($project)){
            throw $this->createNotFoundException('Projet non-trouver(slug');

        }
        return $this->render('project-show.html.twig', compact("project"));


        /*return new Response("<html><body><h1>details de projets: $id</h1></body></html>");*/
    }

    /**
     * @param Project $project
     * @Route("/projet/{slug}")
     * @return Response
     */
    public function show(Project $project):Response{

        //Récupération du Repository

        return $this->render('project-show.html.twig', compact("project"));


    }

    /**
     * @param SessionInterface $session
     * @return Response
     * @Route("/projet/creation")
     */
    public function createOld(SessionInterface $session): Response
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

    /**
     * @return Response
     * @Route("/projet/creation/manuel")
     */
    public function createmanual():Response{

        //creation d'un projet

        $project = new Project();

        //Remplissage du projet

        $project
            ->setName('Project Symfony')

            ->setDescription('ceci est un super project')
            ->setImage('hamac.jpg')
            ->setProgrammedAt(new \DateTime('2018-12-20'))
            ->setIsPublished('true')
            ->setUrl('www.google.com');



        // Récupération de doctrine
        $manager = $this->getDoctrine()->getManager();

        // préparation du sql
        $manager->persist($project);
        // exécution du sql
        $manager->flush();
        //rediraction ver l'accueil

        return $this->redirectToRoute('index');


    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Exception
     * @Route("/projet/creation/formulaire")
     */
    public  function create(Request $request):Response{
        // creation formulaire
        $project = new Project();
        $form = $this->createForm(ProjectType::class,$project);

        //traitment du formulaire

        $form->handleRequest($request);

        //verification valité
        if($form->isSubmitted() && $form->isValid()){

            $project = $form->getData();

            $manger = $this->getDoctrine()->getManager();
            $manger->persist($project);
            $manger->flush();

            return $this->redirectToRoute('index');
        }

        //Renvoi du formulaire
        return $this->render('projet/create.html.twig',[
           'createForm' => $form->createView()

        ]);

        //

    }

    /**
     * @param Project $project
     * @return Response
     * @Route("/projet/{slug}/edition")
     */
    public  function update(Project $project):Response
    {
     // MOdification du projet

        $project->setUrl('https://www.youtube.com/');

     // Récupération du manager
        $manager = $this->getDoctrine()->getManager();

     // Execution SQL
        $manager->flush();
     // Ajout d'un message flush
       $this->addFlash('success', 'votre projet a été modifié');
     //Redirection cer l'accueil
        return $this->redirectToRoute('index');

    }

    /**
     * @param Project $project
     * @return Response
     * @Route("/projet/{slug}/delete")
     */
    public function delete(Project $project):Response{
        if(!$project){
            throw  new NotFoundHttpException("Project non trové");
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($project);
        $em->flush();
        $this->addFlash('danger', 'votre projet a été supprimer');
        return $this->redirectToRoute('index');

    }


}



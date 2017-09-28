<?php

namespace AppBundle\Controller;

use AppBundle\Form\MovieType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Movie;
use Symfony\Component\HttpFoundation\Request;

class AdminMovieController extends Controller
{
    public function listAction()
    {
        $repo = $this->getDoctrine()->getRepository("AppBundle:Movie");
        $movies = $repo->findBy(
            [],
            ["id" => "ASC"],
            50
        );


        return $this->render('AppBundle:Admin:list.html.twig',[
            "movies" => $movies
        ]);


    }

    public function createAction(Request $request)
    {
        $movie = new Movie();

        $movie-> setDateCreated(new \datetime());
        $movie->setDateModified(new \datetime());
        $movie->setRating(0);
        $movie->setVotes(0);

        $form = $this -> createForm(MovieType::class,$movie);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($movie);
            $em->flush();

            $this->addFlash("success","Votre film a bien été enregistré !");

            return $this->redirectToRoute("movie_detail", ["id" => $movie->getId()]);
        }

        return $this->render('AppBundle:Admin:ajout.html.twig',[
            "movieForm"=>$form->createView()
        ]);

    }


    public function editAction($id, Request $request)
    {
        $repo=$this->getDoctrine()->getRepository("AppBundle:Movie");
        $movie=$repo->find($id);

        $movie->setDateModified(new \datetime());

        $form=$this->createForm(MovieType::class,$movie);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($movie);
            $em->flush();

            $this->addFlash("success","Le film a bien été modifié ");

            return $this->redirectToRoute("movie_detail", ["id" => $movie->getId()]);

        }

        return $this->render('AppBundle:Admin:Modifier.html.twig',[
            "movieForm"=>$form->createView()
        ]);
    }

    public function deleteAction($id)
    {
        $repo=$this->getDoctrine()->getRepository("AppBundle:Movie");
        $movie=$repo->find($id);

        $em=$this->getDoctrine()->getManager();
        $em->remove($movie);
        $em->flush();

        $this->addFlash("success","Le film a bien été supprimé");

        return $this->redirectToRoute('app_homepage');

    }




}

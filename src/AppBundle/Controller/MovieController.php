<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Review;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Form\ReviewType;
use Symfony\Component\HttpFoundation\Request;



class MovieController extends Controller
{

    public function listAction($page)
    {
        $pageSuivante = $page + 1;
        $pagePrecedente = $page - 1;

        $repo = $this->getDoctrine()->getRepository("AppBundle:Movie");
        $movies = $repo->findBy(
            [],
            ["year" => "DESC"],
            50,
            ($page - 1)*50
        );

        $counts =$repo->countAll();

        return $this->render('AppBundle:Default:index.html.twig',[
            "movies" => $movies,"page"=>$page, "pageSuivante"=>$pageSuivante,"pagePrecedente"=>$pagePrecedente,"counts"=>$counts
        ]);
    }

    public function detailAction($id,Request $request)
    {
        $repo=$this->getDoctrine()->getRepository("AppBundle:Movie");
        $movie=$repo->find($id);

        if($movie===null)
        {
            throw $this->createNotFoundException("Ce film n'existe pas");
        }

        $review = new Review();
        $form=$this->createForm(ReviewType::class,$review);
        $form->handleRequest($request);

        $review->setMovie($movie);

        if($form->isSubmitted() && $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($review);
            $em->flush();

            $this->addFlash("success","Votre critique a été enregistrée ");

            return $this->redirectToRoute("movie_detail", ["id" => $movie->getId()]);

        }



        return $this->render('AppBundle:Movie:detail.html.twig',[
            "movie"=>$movie, "reviewForm"=>$form->createView()
        ]);



    }

}

<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;

class UserController extends Controller
{
    public function registerAction(Request $request)
    {
        $connectedUser= $this->getUser();
        if($connectedUser){
            $this ->addFlash('warning', 'Vous êtes déjà inscrit dude.');
            return $this->redirectToRoute('app_homepage');
        }

        $user = new User();
        $form = $this->createForm(UserType::class,$user);

        $user->setRoles('ROLE_USER');
        $user->setRegistrationDate(new \DateTime());


        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            //hash le mot de passe
            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encoded);

            //enregistrer dans la base
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash("success", "Vous êtes enregistré !");
            return $this->redirectToRoute('app_homepage');
        }



        return $this->render('AppBundle:User:register.html.twig',[
            "userForm" => $form->createView()
        ]);
    }


    public function loginAction()
    {
        // get the login error if there is one
        $authUtils = $this->get('security.authentication_utils');
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('AppBundle:User:login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);

    }

}

<?php

namespace App\Controller;

use App\Entity\FastConsultation;
use App\Form\FastConsultationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function index(AuthenticationUtils $authenticationUtils,Request $request): Response
    {
            // get the login error if there is one
            $error = $authenticationUtils->getLastAuthenticationError();
            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();

            $fast_consultation = new FastConsultation();
            $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
            $fast_consultation_form->handleRequest($request);
              
            return $this->renderForm('login/index.html.twig', [  
                  'last_username' => $lastUsername,
                  'error'         => $error,
                  'fast_consultation' => $fast_consultation,
                  'fast_consultation_form' => $fast_consultation_form,
                  ]);
            $this->addFlash(
                    'notice',
                    'You success are logged!');         
    }
}

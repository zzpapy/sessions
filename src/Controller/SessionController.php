<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Stagiaire;
use App\Form\SessionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{
    /**
     * @Route("/session", name="session")
     */
    public function index()
    {
        $sessions = $this->getDoctrine()
                    ->getRepository(Session::class)
                    ->findBy([], ['nomSess' => 'ASC']);
        return $this->render('session/index.html.twig', [
            'sessions' => $sessions,
        ]);
    }
    /**
    * @Route("/session/modif/{id}", name="modifSess")
    * @Route("/session/nouvelle", name="creaSess")
    */
    public function creaStag(Request $request,Session $session = null)
    {   
        if(!$session){
            $session = new Session();
        }

        $form = $this->createForm(SessionType::class, $session);
        $form->handleRequest($request);
        // dd($request->request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($session);
            $em->flush();
            return $this->redirectToRoute('session');
        }
        return $this->render('session/creaSess.html.twig', [
            'form' => $form->createView(),
        ]);
    }
     /**
     * @Route("/session/delete/{id}", name="delSess", methods={"GET"})
     */
    public function delete(Session $session = null)
    {
        $sessionId = $session->getId();
        $session = $this->getDoctrine()
                    ->getRepository(Session::class)
                    ->findOneBy(["id" => $sessionId]);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($session);
        $entityManager->flush();
        $this->addFlash('succes', 'session retirée');
        return $this->redirectToRoute('session');
    }
}

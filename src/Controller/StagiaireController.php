<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StagiaireController extends AbstractController
{
    /**
     * @Route("/stagiaire", name="stagiaire")
     */
    public function index()
    {   
        $stagiaires = $this->getDoctrine()
                    ->getRepository(Stagiaire::class)
                    ->findBy([], ['nomStag' => 'ASC']);
        return $this->render('stagiaire/index.html.twig', [
            'stagiaires' => $stagiaires,
        ]);
    }
    /**
     * @Route("/stagiaire/detail/{id}", name="detailStag")
     */
    public function detail(Stagiaire $stagiaire)
    {   
        
        return $this->render('stagiaire/detail.html.twig', [
            'stagiaire' => $stagiaire,
        ]);
    }
    /**
    * @Route("/stagiaire/modif/{id}", name="modifStag")
    * @Route("/stagiaire/nouveau", name="creaStag")
    */
    public function creaStag(Request $request,Stagiaire $stagiaire = null)
    {   
        if(!$stagiaire){
            $stagiaire = new Stagiaire();
        }

        $form = $this->createForm(StagiaireType::class, $stagiaire);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
           
            $em = $this->getDoctrine()->getManager();
            $em->persist($stagiaire);
            $em->flush();
            return $this->redirectToRoute('stagiaire');
            
        }
        return $this->render('stagiaire/creaStag.html.twig', [
            'form' => $form->createView(),
        ]);
    }
     /**
     * @Route("/stagiare/delete/{id}", name="delStag", methods={"GET"})
     */
    public function delete(Stagiaire $stagiaire = null)
    {
        $stagiaireId = $stagiaire->getId();
        $stagiaire = $this->getDoctrine()
                    ->getRepository(Stagiaire::class)
                    ->findOneBy(["id" => $stagiaireId]);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($stagiaire);
        $entityManager->flush();
        $this->addFlash('succes', 'stagiaire retirée');
        return $this->redirectToRoute('stagiaire');
    }
}

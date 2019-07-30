<?php

namespace App\Controller;
use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use  App\Entity\Depot;
use App\Form\DepotType;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
/**
 * @Security("has_role('ROLE_CAISSIER')")
 */
class DepotController extends FOSRestController
{
    /**
     * @Route("/api/depot",name="depot",methods={"POST"})
     */
    public function versement(Request $request)
    {
        $depot = new Depot();
        $form=$this->createForm(DepotType::class,$depot);
        $data=json_decode($request->getContent(),true);
        $form->submit($data);
        if($form->isSubmitted() && $form->isValid()){
            $depot->setDateDepot(new \Datetime());
            $user = $this->getUser();
            $depot->setCaissier($user->getId());

            var_dump($data);
            $em=$this->getDoctrine()->getManager();
            $em->persist($depot);
            $em->flush();
        $partenaire=$depot->getPartenaire();
        $value=$partenaire->getSolde()+$depot->getMontant();
        $partenaire->setSolde($value);
        $em->persist($partenaire);
        $em->flush();
        
            return $this->handleView($this->view(['status'=>'Depot validé'],Response::HTTP_CREATED));

        }
        return $this->handleView($this->view($form->getErrors()));

        
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use  App\Entity\Partenaire;
use App\Repository\PartenaireRepository;
/**
 * @Route("/api")
 */

class PartenaireController extends AbstractController
{
    /**
     * @Route("/partenaire", name="partenaire", methods={"POST"})
     */
    public function ajout(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        
        $partenaire = $serializer->deserialize($request->getContent(),Partenaire::class,'json');
        $entityManager->persist($partenaire);
        $entityManager->flush();
        $data = [
            'status' => 201,
            'message' => 'Le partenaire a bien été ajouté'
        ];
        return new JsonResponse($data, 201);
    }
    /**
     * @Route("/listepartenaire", name="list_partenaire", methods={"GET"})
     */
    public function list(PartenaireRepository $repo,SerializerInterface $serializer)
    {
        $partenaires = $repo->findAll();
        $data = $serializer->serialize($partenaires, 'json');
        return new Response($data, 200, [
            'Content-Type' => 'application/json'


        ]);

    }
}

<?php

namespace App\Controller;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use  App\Entity\Partenaire;
use App\Repository\PartenaireRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api")
 */

class PartenaireController extends AbstractController
{
    /**
     * @Route("/partenaire", name="partenaire", methods={"POST"})
     */
    public function ajout(Request $request,UserPasswordEncoderInterface $passwordEncoder, SerializerInterface $serializer, EntityManagerInterface $entityManager,ValidatorInterface $validator)
    {
        
        $partenaire = $serializer->deserialize($request->getContent(),Partenaire::class,'json');
        $entityManager->persist($partenaire);
        $entityManager->flush();
        $data = [
            'status' => 201,
            'message' => 'Le partenaire a bien été ajouté'
        ];

        $user = new User();
            $user->setEmail($partenaire->getEmail());
            $user->setPassword($passwordEncoder->encodePassword($user, 'passer'));
            $user->setRoles(['ROLE_AdminPartenaire']);
            $user->setNomcomplet($partenaire->getRaisonsociale());
            $user->setPropriete($partenaire->getId());
            $user->setAdresse($partenaire->getAdresse());
            $user->setTelephone($partenaire->getTelephone());
            $user->setStatut('actif');
            
            $entityManager->persist($user);
            $entityManager->flush();
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
    /**
     * @Route("/partenaire/{id}", name="updatepartenaire", methods={"PUT"})
     */
    public function update(Request $request, SerializerInterface $serializer, Partenaire $partenaire, ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        $partenaireUpdate = $entityManager->getRepository(Partenaire::class)->find($partenaire->getId());
        $data = json_decode($request->getContent());
        foreach ($data as $key => $value){
            if($key && !empty($value)) {
                $name = ucfirst($key);
                $setter = 'set'.$name;
                $partenaireUpdate->$setter($value);
            }
        }
        $errors = $validator->validate($partenaireUpdate);
        if(count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json'
            ]);
        }
        $entityManager->flush();
        $data = [
            'status' => 200,
            'message' => 'Le partenaire a bien été mis à jour'
        ];
        return new JsonResponse($data);
    }


}

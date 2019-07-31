<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/** 
 * @Route("/api")
 */
class SecurityController extends AbstractController
{

/** 
 * @Route("/register", name="api_register", methods={"POST"})
 * @Security("has_role('ROLE_SUPERADMIN') or has_role('ROLE_AdminPartenaire')")
 */

public function register(ObjectManager $om, UserPasswordEncoderInterface $passwordEncoder, Request $request)
{

   $user = new User();

   $email                  = $request->request->get("email");
   $password               = $request->request->get("password");
   $passwordConfirmation   = $request->request->get("password_confirmation");
   $roles                  = $request->request->get("roles");
   $nomcomplet             = $request->request->get("nomcomplet");
   //$propriete              = $request->request->get("propriete");
   $adresse                = $request->request->get("adresse");
   $telephone              = $request->request->get("telephone");
   $statut                 = $request->request->get("statut");

   $errors = [];
   if($password != $passwordConfirmation)
   {
     $errors[] = "Mots de Passe ne correspondent pas.";
   }

   if(strlen($password) < 6)
   {
     $errors[] = "Mot de Passe doit etre superieur ou égal à 6 caractéres.";
   }

   if(!$errors)
   {
        $encodedPassword = $passwordEncoder->encodePassword($user, $password);
        $user->setEmail($email);
        $user->setPassword($encodedPassword);
        $user->setRoles($roles);
        $user->setNomcomplet($nomcomplet);
        $utilisateur = $this->getUser();
        if($utilisateur->getRoles()[0]=='ROLE_SUPERADMIN'){
            $user->setPropriete('WARI');
        }
        else if($utilisateur->getRoles()[0]=='ROLE_AdminPartenaire'){
                $user->setPropriete($utilisateur->getPropriete());
        }
        //$user->setPropriete($propriete);
        $user->setAdresse($adresse);
        $user->setTelephone($telephone);
        $user->setStatut($statut);


        try
        {
            $om->persist($user);
            $om->flush();
           
                      $data = [
                'status' => 201,
                'message' => 'L\'utilisateur a été créé'
            ];
            return new JsonResponse($data, 201);
        }
        catch(UniqueConstraintViolationException $e)
        {
            $errors[] = "l'email fourni existe déja !";
        }
        catch(\Exception $e)
        {
            $errors[] = "Unable to save new user at this time.";
        }
   }
  
   return $this->json([
     'errors' => $errors
   ], 400);
}



    /**
     * @Route("/login", name="login", methods={"POST"})
     */
    public function login(Request $request)
    {
        $user = $this->getUser();
        return $this->json([
            'email' => $user->getUsername(),
            'roles' => $user->getRoles()
        ]);

        
    }

    /** 
     * @Route("/user/{id}", name="statut", methods={"PUT"})
     * @Security("has_role('ROLE_SUPERADMIN') or has_role('ROLE_AdminPartenaire')")
     */
    public function statut(User $user,EntityManagerInterface $entityManager)
    {
        if($user->getStatut()=="actif")
        {
            $user->setStatut("bloquer");
        
        }
        else
        {
            $user->setStatut("actif");
        
        }
        $entityManager->persist($user);
        $entityManager->flush();
        $data = [
            'status' => 200,
            'message' => 'Le partenaire a bien été mis à jour'
        ];
        return new JsonResponse($data);
    }

}
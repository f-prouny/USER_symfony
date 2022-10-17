<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserController extends 
AbstractController
{
    /**
     * @Route("/", name="app_user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig' );
    }
    /**
     * @Route("/inscription",name="registration")
     */
    public function inscription(ManagerRegistry $doctrine,Request $resq, UserPasswordEncoderInterface $encoder, SluggerInterface $slugger){
        $user = new User();
        $form = $this->createForm(UserType::class,$user);

        $form->handleRequest($resq);
        if($form->isSubmitted()&& $form->isValid()){
            $hash = $encoder($user,$user->getPassword
            ());
        $user->setPassword($hash);

        // ajout photo de profil
        /** @var UploadedFile $imgFile
        
         */
        $imgFile = $form->get('avatar')->getData();
        if($imgFile){
            $originalFileName = pathinfo
            ($imgFile->getClientOriginalName(),
            PATHINFO_FILENAME);
            $safeFilmName = $slugger->slug($originalFileName);
            $newFileName= $safeFilmName."-".uniqid().".".
            $imgFile->guessExtension();

            try {
              $imgFile->move($this->getParameter('avatar'),
              $newFileName);  
            } catch (FileException $th) {
                $th->getMessage();

            }
            $user->setAvatar($newFileName);
        }
        $objectManager = $doctrine->getManager();
        }

        return $this->render("user/add.html.twig",[
            "formulaire"=>$form->createView()
        ]);
    }
}

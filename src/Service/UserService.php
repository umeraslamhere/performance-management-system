<?php

namespace App\Service;

use App\Entity\User;
use Psr\Log\LoggerInterface;

class UserService{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger; 
    }
    public function saveToDatabase( $request , $userPasswordEncoderInterface , $user_status , $em) {
        
        $user = new User();
        $user->setEmail($request->request->get('email'));
        $user->setFirstName('its req, make it nullable or add field in form');
        $user->setPassword($userPasswordEncoderInterface->encodePassword(
            $user, $request->request->get('password')
        ));
        $roles[] = 'ROLE_USER';
        $user->setRoles($roles);
        $user->setStatus($user_status);
        $em->persist($user);
        $em->flush();

        $this->logger->info('Password reset successfully');

        return $user;
    }

    public function testUploadingImage($request , $destination){
        /** 
        * @var UploadedFile $uploadedFile
        */
        $uploadedFile = $request->files->get('image');
        
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(),PATHINFO_FILENAME); //GIVES ORIGINAL NAME WITHOUT FILE EXTENSION
        $newFilename = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension(); //securing name by placing unique id with filename
        dd($uploadedFile->move($destination ,$newFilename));
    }

    public function uploadUserPicture($user, $uploadedFile, $destination, $entityManager){
            
        /** 
         * @var UploadedFile $uploadedFile
         */
        $newFilename='';
        if($uploadedFile){

            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(),PATHINFO_FILENAME); //GIVES ORIGINAL NAME WITHOUT FILE EXTENSION
            $newFilename = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension(); //securing name by placing unique id with filename
            $uploadedFile->move($destination ,$newFilename);

        }else{
            $newFilename = $user->getPicture();
        }

        $user->setPicture($newFilename);


        $entityManager->flush();
    }
}

?>
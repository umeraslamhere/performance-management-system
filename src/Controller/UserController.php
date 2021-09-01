<?php

namespace App\Controller;

use App\Form\UserProfileForm;
use App\Repository\UserRepository;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    private $userRepository;
    private $userService;
    public function __construct(UserRepository $userRepository , UserService $userService)
    {
        $this->userRepository = $userRepository;
        $this->userService = $userService;
    }

    /**
     * @Route("/" , name="home")
     * @Method({"GET"})
     */

    public function home(Request $request): Response
    {
        $form = $this->createForm(LoginForm::class);
        $form->handleRequest($request); //only renders if form type is post 
   
        return $this->render('welcome.html.twig' , array(
            'form' =>$form->createView() 
        ));
    }

    /**
     * @Route("/user/show/{id}" , name="my_profile")
     * Method({"GET"})
     */
    public function show(Request $request, $id)
    {
        $user = $this->userRepository->findUserBy(['id' => $id]);
        $form = $this->createForm(UserProfileForm::class , $user);
        $form->handleRequest($request);
        
        return $this->render('/users/show.html.twig' , array(
            'form' =>$form->createView(),
            'id' => $id 
        ));
    }

    /**
     * @Route("/dashboard" , name="user_dashboard")
     * @Method({"GET"})
     */

    public function dashboard(Request $request): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        return $this->render('dashboard.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/admin/users" , name="users")
     * @Method({"GET"})
     */
    public function getUsers(): Response
    {
        $users=$this->userRepository->findAllUsers();
        return $this->render('admin/users/index.html.twig', array('users' => $users));
    }
    

    /**
    * @Route("/testing_file_uploading", name="upload_test")  
    */

    public function temporaryUploadAction(Request $request){
        $destination= $this->getParameter('kernel.project_dir').'/public/uploads'; //kernal.project_dir will get root of our app
        $this->userService->testUploadingImage($request , $destination);
    }


     /**
     * @Route("/user/update/{id}" , name="update_user")
     * Method({"GET" , "POST"})
     */
    public function update(Request $request , $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->userRepository->findUserBy(['id' => $id]);
        $form = $this->createForm(UserProfileForm::class  ,$user);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $destination= $this->getParameter('kernel.project_dir').'/public/uploads/user_images'; //kernal.project_dir will get root of our app
            $uploadedFile = $form['picture']->getData();
            $this->userService->uploadUserPicture($user, $uploadedFile , $destination , $entityManager);

            return $this->redirectToRoute('my_profile', [
                'id' => $user->getId(),
            ]);
        }
            
        return $this->render('/users/edit.html.twig' , array(
            'form' =>$form->createView() 
        ));
    }

    /**
     * @Route("/user/get-all-managers" , name="all_managers")
     * Method({"GET" , "POST"})
     */
    public function getAllManagers(Request $request){
        
    }
}
    
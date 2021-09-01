<?php

namespace App\Controller;

use App\Entity\User; //just like we had models for Eloquent
use App\Entity\Team; //just like we had models for Eloquent
use App\Repository\UserRepository;
use App\Form\UserProfileForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    private $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/signup_requests" , name="signup_requests")
     * @Method({"GET"})
     */
    public function signup_requests(): Response
    {
        $signup_requests=$this->userRepository->findAllUsersBy(['status' => 'pending']);
        return $this->render('admin/users/index.html.twig', array('signup_requests' => $signup_requests));
    }

    /**
     * @Route("/admin/users" , name="all_users")
     * @Method({"GET"})
     */
    public function all_users(): Response
    {
        $users=$this->userRepository->findAllUsers();
        return $this->render('admin/users/all_users.html.twig', array('users' => $users));
    }

    /**
     * @Route("/admin/teams" , name="all_teams")
     * @Method({"GET"})
     */
    public function all_teams(): Response
    {
        $teams=$this->getDoctrine()->getRepository(Team::class)->findAll();        
        return $this->render('admin/users/all_teams.html.twig', array('teams' => $teams));
    }

    /**
     * @Route("/admin/teams/create" , name="create_team")
     * @Method({"GET"})
     */
    public function create_team(): Response
    {
        $users=$this->userRepository->findAllUsers();
        $project_managers = [];
        foreach($users as $user){
            if(in_array("ROLE_PM", $user->getRoles())){
                array_push($project_managers, $user);
            }
        }
        return $this->render('admin/teams/create.html.twig' , array('project_managers' => $project_managers));
    }

     /**
     * @Route("/request/decline_request/{id}" , name="decline_request")
     * Method({"POST"})
     */
    public function delete($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        $this->userRepository->removeUser($id);
        $signup_requests=$this->userRepository->findAllUsersBy(['status' => 'pending']);
        
        $entityManager->flush();
        $this->addFlash(
            'notice',
            'Request Declined!'
        );
        return $this->redirectToRoute('signup_requests',array(
            'signup_requests' => $signup_requests,
        ));
    }

     /**
     * @Route("/request/approve_request/{id}" , name="approve_request")
     * Method({"POST"})
     */
    public function approve_request($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->userRepository->findUserById($id);
        $user->setStatus('approved');
        $signup_requests=$this->userRepository->findAllUsersBy(['status' => 'pending']);
        
        $entityManager->flush();
        $this->addFlash(
            'notice',
            'Request Approved!'
        );

        return $this->redirectToRoute('signup_requests',array(
            'signup_requests' => $signup_requests,
        ));
    }

    /**
     * @Route("/request/details/{id}" , name="details")
     */
    public function show($id)
    {
        $user=$this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $id]);
        return $this->render('admin/users/show.html.twig', array('user' => $user));
    }

     /**
     * @Route("/request/update/{id}" , name="update_request")
     * Method({"GET" , "POST"})
     */
    public function update_request(Request $request , $id)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = $this->userRepository->findUserById($id);
        $form = $this->createForm(UserProfileForm::class , $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->flush();
            $signup_requests=$this->userRepository->findAllUsersBy(['status' => 'pending']);

            return $this->redirectToRoute('signup_requests', [
                'signup_requests' => $signup_requests,
                'id' => $user->getId(),
            ]);
        }
            
        return $this->render('admin/users/edit.html.twig' , array(
            'form' =>$form->createView() 
        ));
    }

}

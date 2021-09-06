<?php

namespace App\Controller;

use App\Entity\Team; //just like we had models for Eloquent
use App\Entity\User;
use App\Form\TeamForm;
use App\Repository\UserRepository;
use App\Form\UserProfileForm;
use App\Repository\TeamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    
    private $userRepository;
    private $teamRepository;
    public function __construct(UserRepository $userRepository, TeamRepository $teamRepository)
    {
        $this->userRepository = $userRepository;
        $this->teamRepository = $teamRepository;
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
        $all_teams = array();

        
        //dd($all_teams);
        return $this->render('admin/users/all_teams.html.twig', ['teams' => $teams]);
    }

    /**
     * @Route("/admin/team/{id}" , name="show_team")
     * @Method({"GET"})
     */
    public function showTeam(Request $request , Team $team){
        return $this->render('admin/teams/show_team.html.twig' , [
            'team' => $team,
        ]);
    }

    /**
     * @Route("/admin/teams/create" , name="create_team")
     * @Method({"GET" ,"POST"})
     */
    public function create_team(Request $request): Response
    {
        $team = new Team();
        $form = $this->createForm(TeamForm::class , $team);
        $form->handleRequest($request);
        if($request->isMethod('POST')){

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($team);
            $entityManager->flush();

            $team->addMemberId($form['memberId']->getData());
            
            $teams = $this->teamRepository->findAllTeams();
            //dd('ok');
            return $this->redirectToRoute('all_teams',array(
                'teams' => $teams
                ));
        }
         
        return $this->render('admin/teams/create.html.twig' , array(
            'form' =>$form->createView() 
           // 'managers' => $project_managers
        ));
    }

     /**
     * @Route("/request/decline_request/{id}" , name="decline_request")
     * Method({"POST"})
     */
    public function delete(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        $this->userRepository->removeUser($id);
        $signup_requests=$this->userRepository->findAllUsersBy(['status' => 'pending']);
        
        $entityManager->flush();
        $this->addFlash(
            'notice',
            'Request Declined!'
        );
        return $this->redirect($request->headers->get('referer'));
    }

     /**
     * @Route("/request/approve_request/{id}" , name="approve_request")
     * Method({"POST"})
     */
    public function approve_request($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->userRepository->findUserBy(['id' => $id]);
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
        $user = $this->userRepository->findUserBy(['id' => $id]);
        return $this->render('admin/users/show.html.twig', array('user' => $user));
    }

     /**
     * @Route("/request/update/{id}" , name="update_request")
     * Method({"GET" , "POST"})
     */
    public function update_request(Request $request , $id)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = $this->userRepository->findUserBy(['id' => $id]);
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

<?php

namespace App\Controller;

use App\Form\AddToTeamForm;
use App\Form\UserProfileForm;
use App\Repository\CommentRepository;
use App\Repository\TeamRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{
    private $teamRepository;
    private $userRepository;
    private $commentRepository;
    public function __construct( TeamRepository $teamRepository , UserRepository $userRepository , CommentRepository $commentRepository)
    {
        $this->teamRepository = $teamRepository;
        $this->userRepository = $userRepository;
        $this->commentRepository = $commentRepository;
    }
    /**
     * @Route("/team", name="team")
     */
    public function index(): Response
    {
        return $this->render('team/index.html.twig', [
            'controller_name' => 'TeamController',
        ]);
    }

    /**
     * @Route("/teams/details/{id}", name="team_details")
     */
    public function details($id): Response
    {
        $team = $this->teamRepository->findOneBy(['id' => $id]);
        $teamMembers = $this->teamRepository->teamMemebers($id);
        return $this->render('/users/newsfeed/team_details.html.twig', [
            'team' => $team,
            'teamMembers' => $teamMembers,
        ]);
    }
    
    /**
     * @Route("/teams/add_team_member/{id}", name="add_team_member")
     * * Method({"GET","POST"})
     */
    public function add_team_member(Request $request, $id): Response
    {
        $team = $this->teamRepository->findOneBy(['id' => $id]);
        $teamMembers = $this->teamRepository->teamMemebers($team->getId());
        $teamMemberIds =[];
        foreach($teamMembers as $teamMember){
            array_push($teamMemberIds, $teamMember['id']);
        }
        $teamManagerId  = $team->getManagerId()->getId();
        $form = $this->createForm(AddToTeamForm::class , $team,  [
            'teamId' => $team->getId(),
            'managerId' => $teamManagerId,
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //dd('save data to team table');
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($team);
            $entityManager->flush();
            foreach($form['memberId']->getData()->getValues() as $member){ //get values bcz data is in persistentCollecction Form
                $team->addMemberId($member); 
            }
            
            $teams = $this->teamRepository->findAllTeams();
            dd('ok stored');
            // return $this->redirectToRoute('all_teams',array(
            //     'teams' => $teams
            //     ));
        }
        return $this->render('/team/add_to_team.html.twig' , array(
            'form' =>$form->createView(),
            'id' => $id,
        ));
        dd($id);
    }
    /**
     * @Route("/teams/details/team_member/{id}" , name="team_member_details")
     * Method({"GET"})
     */
    public function team_member_details(Request $request, $id)
    {
        $user = $this->userRepository->findUserBy(['id' => $id]);
        $form = $this->createForm(UserProfileForm::class , $user);
        $form->handleRequest($request);
        $loggedInManager = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $comments = $this->commentRepository->getComments($loggedInManager, $user->getId());
        return $this->render('/users/show.html.twig' , array(
            'form' =>$form->createView(),
            'id' => $id,
            'comments' => $comments,
            'user' => $user
        ));
    }


}

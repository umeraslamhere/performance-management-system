<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentForm;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use Egyg33k\CsvBundle\Egyg33kCsvBundle;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use League\Csv\Writer;

class CommentController extends AbstractController
{
    private $commentRepository;
    private $userRepository;
    public function __construct(CommentRepository $commentRepository , UserRepository $userRepository )
    {
        $this->commentRepository= $commentRepository;
        $this->userRepository= $userRepository;
    }
    /**
     * @Route("/comment", name="comment")
     */
    public function index(): Response
    {
        return $this->render('comment/index.html.twig', [
            'controller_name' => 'CommentController',
        ]);
    }

    
    /**
     * @Route("/comment/add_feedback/{id}", name="feedback")
     */
    public function feedback(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $comment = $this->commentRepository->findOneBy(['id' => $id]);
        $comment->setFeedback($request->request->get('feedback'));
        $entityManager->flush();
        $this->addFlash(
            'notice',
            'Feedback added successfully!'
        );
        return $this->redirect($request->headers->get('referer'));
    }

    

    /**
     * @Route("/export-comments/{id}", name="export_comments")
     */
    public function exportAction(Request $request,$id)
    { 
        $header = ['#' ,'CommentOn', 'CommentBy', 'Content' , 'Feedback'];
        $csv = Writer::createFromString();
        $csv->insertOne($header);        
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $commentOn = $this->userRepository->findUserBy(['id' => $loggedInUser]);
        $comments = $this->commentRepository->getComments($id , $commentOn->getId()); //by,on
        $csv->insertAll($comments);
        $csv->output('comments.csv');
        $this->addFlash(
            'notice',
            'Comments exported successfully!'
        );
        dd(''); 
        return $this->redirect($request->headers->get('referer'));
    }


    /**
     * @Route("/managers/comment/{id}", name="comments_by_manager")
     */
    public function comments_by_manager($id)
    {
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $commentOn = $this->userRepository->findUserBy(['id' => $loggedInUser]);
        $comments = $this->commentRepository->getComments($id , $commentOn->getId()); //by,on
        return $this->render('comment/index.html.twig', [
            'comments' => $comments,
            'managerId' => $id
        ]);
    }

    /**
     * @Route("/comments/deleteComment/{id}", name="remove_comment")
     */
    public function removeComment(Request $request , $id)
    {
        $this->commentRepository->removeComment($id);
        $this->addFlash(
            'notice',
            'Your comment has been deleted successfully!'
        );
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/comments/addComment/{id}", name="add_comment")
     */
    public function addComment(Request $request , $id)
    {
        $comment = new Comment();
        $commentOn = $this->userRepository->findUserBy(['id' => $id]);
        $loggedInManager = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $commentBy = $this->userRepository->findUserBy(['id' => $loggedInManager]);

        $form = $this->createForm(CommentForm::class , $comment,  [
            'commentBy' => $commentBy,
            'commentOn' => $commentOn,
        ]);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            
            $entityManager = $this->getDoctrine()->getManager();
            $comment = new Comment();
            $comment->setSubordinateId($commentOn);
            $comment->setManagerId($commentBy);
            $comment->setContent($form['content']->getData());
            $entityManager->persist($comment);
            $entityManager->flush();
            
            $this->addFlash(
                'notice',
                'Your comment has been added successfully!'
            );
            return $this->redirect($request->headers->get('referer')); 
            /* $form = $this->createForm(UserProfileForm::class , $commentOn); //the current Subordinate (BAD CODING)
            $comments = $this->commentRepository->getComments($loggedInManager, $commentOn->getId()); //the current Manager ID & Subordinate ID (BAD CODING)

            return $this->render('/users/show.html.twig' , array(
                'form' =>$form->createView(),
                'id' => $id,
                'comments' => $comments,
                'user' => $commentOn //the current Subordinate (BAD CODING)
            )); */
        }
        return $this->render('/comment/form.html.twig' , array(
            'form' =>$form->createView(),
        ));
    }

}

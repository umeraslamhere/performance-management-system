<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\LoginFormAuthenticator;
use App\Service\UserService;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Cache\Traits\EmailTrait;
Use Psr\Log\LoggerInterface;

class SecurityController extends AbstractController
{
    use EmailTrait;
    public $session;
    public $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->session = new Session();
        $this->userRepository=$userRepository;
    }       

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /** 
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('Will be intercepted before getting here!');
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request ,AuthenticationUtils $authenticationUtils, UserPasswordEncoderInterface $userPasswordEncoderInterface ,GuardAuthenticatorHandler $guardAuthenticatorHandler , LoginFormAuthenticator $loginFormAuthenticator , UserService $userService )
    {
        if($request->isMethod('POST')){
            $user_status = 'pending';
            $em=$this->getDoctrine()->getManager();
            $user = $userService->saveToDatabase($request , $userPasswordEncoderInterface, $user_status , $em);
            $error = $authenticationUtils->getLastAuthenticationError();
            $lastUsername = $authenticationUtils->getLastUsername();

            $this->addFlash(
                'notice',
                'Registered Successfully!'
            );
            return $this->redirectToRoute('app_login',array(
                'last_username' => $lastUsername, 'error' => $error
                ));
        }
        return $this->render('security/register.html.twig');
    }

    /**
     * @Route("/register-via-link", name="app_register_via_link")
     */
    public function registerViaLink(\Swift_Mailer $swiftMailer,LoggerInterface $loggerInterface, Request $request , UserPasswordEncoderInterface $userPasswordEncoderInterface ,GuardAuthenticatorHandler $guardAuthenticatorHandler , LoginFormAuthenticator $loginFormAuthenticator ):Response
    {
        if($request->isMethod('POST')){
            $this->signup_link($loggerInterface, $swiftMailer , $request->request->get('email'));
            

            

            return new Response('<html><body>Please Check your inbox.</body></html>');
        }
        return $this->render('security/register_via_link.html.twig');
    }

    /**
     * @Route("/request_reset_password", name="app_request_reset_password")
     */
    public function requestResetPassword(\Swift_Mailer $swiftMailer,LoggerInterface $loggerInterface, Request $request , UserPasswordEncoderInterface $userPasswordEncoderInterface ,GuardAuthenticatorHandler $guardAuthenticatorHandler , LoginFormAuthenticator $loginFormAuthenticator ):Response
    {
        if($request->isMethod('POST')){
            $this->reset_password($loggerInterface, $swiftMailer , $request->request->get('email'));
            
            $this->session->set('email', $request->request->get('email'));
            return new Response('<html><body>Please Check your inbox.</body></html>');
        }
        return $this->render('security/request_reset_password.html.twig');
    }

    /**
     * @Route("/set_password" , name="app_set_password")
     */
    public function registerViaLinkSetPassword(Request $request , AuthenticationUtils $authenticationUtils, UserPasswordEncoderInterface $userPasswordEncoderInterface ,GuardAuthenticatorHandler $guardAuthenticatorHandler , LoginFormAuthenticator $loginFormAuthenticator , UserService $userService)
    {
        if($request->isMethod('POST')){
            if($request->request->get('password') === $request->request->get('confirm_password')){
                
                $em=$this->getDoctrine()->getManager();
                $user_status = 'approved';
                $user = $userService->saveToDatabase($request , $userPasswordEncoderInterface , $user_status , $em);
                $error = $authenticationUtils->getLastAuthenticationError();
                $lastUsername = $authenticationUtils->getLastUsername();
    
                $this->addFlash(
                    'notice',
                    'Registered Successfully!'
                );
                return $this->redirectToRoute('app_login',array(
                    'last_username' => $lastUsername, 'error' => $error
                    ));
                /* return $guardAuthenticatorHandler->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $loginFormAuthenticator,
                    'main' //provider key, name of our firewall
                ); */
            }else{
                $this->addFlash(
                    'notice',
                    'Password does not match with Confirm Password!'
                );

                return $this->render('security/set_password.html.twig');
            }
        }
        return $this->render('security/set_password.html.twig');
    }
    
    /**
     * @Route("/reset_password" , name="app_reset_password")
     */

    public function resetPassword(Request $request , UserPasswordEncoderInterface $userPasswordEncoderInterface ,GuardAuthenticatorHandler $guardAuthenticatorHandler , LoginFormAuthenticator $loginFormAuthenticator , AuthenticationUtils $authenticationUtils)
    {
        if($request->isMethod('POST')){
            if($request->request->get('password') === $request->request->get('confirm_password')){
                $user = $this->userRepository->findUserBy(['email' => $request->request->get('email')]);

                if($user){
                    $user->setPassword($userPasswordEncoderInterface->encodePassword(
                        $user, $request->request->get('password')
                    ));
                    $em=$this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->flush();
                    
                    
                    $this->addFlash(
                        'notice',
                        'Your Password is reset successfully!'
                    );
                    $lastUsername = $authenticationUtils->getLastUsername();
                    $error = $authenticationUtils->getLastAuthenticationError();
                    return $this->redirectToRoute('app_login',array(
                        'last_username' => $lastUsername, 'error' => $error
                        ));
                    
                }else{
                    $this->addFlash(
                        'notice',
                        'No User with this email found!'
                    );
                    return $this->render('security/reset_password.html.twig');
                }
            }else{
                dd('ok404');
                $this->addFlash(
                    'notice',
                    'Password does not match with Confirm Password!'
                );
                return $this->render('security/reset_password.html.twig');
            }
        }
        return $this->render('security/reset_password.html.twig');
    }

}

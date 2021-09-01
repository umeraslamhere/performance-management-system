<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;
    private $userRepository;
    private $routerInterface;
    private $csrfTokenManagerInterface;
    private $userPasswordEncoderInterface;

    public function __construct(UserRepository $userRepository , RouterInterface $routerInterface , CsrfTokenManagerInterface $csrfTokenManagerInterface , UserPasswordEncoderInterface $userPasswordEncoderInterface) 
    {
        $this->userRepository = $userRepository;    
        $this->routerInterface = $routerInterface;    
        $this->csrfTokenManagerInterface = $csrfTokenManagerInterface;    
        $this->userPasswordEncoderInterface = $userPasswordEncoderInterface;    
    }
    public function supports(Request $request)
    {
        //die('okkkkkkkk');

        //to check if url is /login
        return $request->attributes->get('_route') === 'app_login' && $request->isMethod('POST');

        //if return false, nothing will happen
        //if return true (when url is /login and POST request), getCredentials will be called
    }

    public function getCredentials(Request $request)
    {
        //dd($request->request->all());
        $credentials= [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),

        ];

        $request->getSession()->set(
            Security::LAST_USERNAME, $credentials['email']
        );

        return $credentials;
        //now this will be passed to getUsers
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token =  new CsrfToken('authenticate' , $credentials['csrf_token']);
        if(!$this->csrfTokenManagerInterface->isTokenValid($token)){
            throw new InvalidCsrfTokenException();
        }
        return $this->userRepository->findOneBy(['email' => $credentials['email']]);
        //if returns false, will get error msg
        //otherwise checkCredentials will be called
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        //dd($user );

        //check for passwords here
        //return true;
        //now onAuthSuccess will be called

        //when password is encoded, use below
        return $this->userPasswordEncoderInterface->isPasswordValid($user , $credentials['password']);
    }


    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
       /*  dd('success');  */
       return new RedirectResponse($this->routerInterface->generate('user_dashboard'));
       //if you are not logged in and try to get admin/signup_reuqests, you'll be redirected back to login. from there
                //if you login, you will be redirected to app_login instead of the route you request i.e. admin/signup_requests
                //the following if check solves this problem
        if($targetPath = $this->getTargetPath($request->getSession(), $providerKey)){
            return new RedirectResponse($targetPath);
        }
       //return new RedirectResponse($this->routerInterface->generate('dashboard'));
    }

    protected function getLoginUrl()
    {
       return $this->routerInterface->generate('app_login');
    }

}

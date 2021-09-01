<?php

namespace Symfony\Component\Cache\Traits;

Use Psr\Log\LoggerInterface;

/**
 * @author Nicolas Grekas <p@tchwork.com>
 *
 * @internal
 */
trait EmailTrait
{

    /**
    * @var \Closure needs to be set by class, signature is function(string <key>, mixed <value>, bool <isHit>)
    */

    public function mailTest(LoggerInterface $logger, $mailer)
    {
        $body="hello This is the body of my mail";
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('umer.aslam@coeus-solutions.de')
            ->setTo('itsumeraslam@gmail.com')
            ->setBody(
                $this->renderView(
                    // templates/emails/registration.html.twig
                    'email/index.html.twig'
                ),
                'text/html'
            )
        ;
        $mailer->send($message);
        $logger->info('Email Sent');
    }
    /**
    * @var \Closure needs to be set by class, signature is function(string <key>, mixed <value>, bool <isHit>)
    */

    public function signup_link(LoggerInterface $logger, $mailer, $email)
    {
        $body="Thank you for Signing Up. Click the link to set password.";
        $message = (new \Swift_Message('Hello Email')) 
            ->setFrom('umer.aslam@coeus-solutions.de')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                    // templates/emails/registration.html.twig
                    'email/set_password.html.twig'
                ),
                'text/html'
            )
        ;
        $mailer->send($message);
        $logger->info('Email Sent');
    }
    /**
    * @var \Closure needs to be set by class, signature is function(string <key>, mixed <value>, bool <isHit>)
    */

    public function reset_password(LoggerInterface $logger, $mailer, $email)
    {
        //$body="Seems like you forgot your password. But no worries, we'll reset it for you.";
        $message = (new \Swift_Message('Reset Password')) 
            ->setFrom('umer.aslam@coeus-solutions.de')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                    // templates/emails/registration.html.twig
                    'email/reset_password.html.twig'
                ),
                'text/html'
            )
        ;
        $mailer->send($message);
        $logger->info('Email Sent');
    }
}

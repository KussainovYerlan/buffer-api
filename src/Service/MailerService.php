<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;   
    }

    public function sendConfirmationEmail(string $userEmail, string $verificationToken)
    {
        $email = (new Email())
            ->from('tvoya@mam.ka')
            ->to($userEmail)
            ->subject('Email verification')
            ->text('http://127.0.0.1:8000/api/confirm?token=' . $verificationToken)
        ;

        $sentEmail = $this->mailer->send($email);
    }
}
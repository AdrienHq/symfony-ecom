<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;


class MailerTestController extends AbstractController
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    #[Route('/test-email', name: 'test_email')]
    public function sendTestEmail(): Response
    {
        $email = (new Email())
            ->from('contact@niftynoms.com')
            ->to('adrienhecq@example.com')
            ->subject('Test Email')
            ->text('This is a test email.');

        $this->mailer->send($email);

        return new Response('Email sent successfully');
    }
}

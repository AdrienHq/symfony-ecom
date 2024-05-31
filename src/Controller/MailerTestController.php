<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;

class MailerTestController extends AbstractController
{
    private $mailer;
    private $logger;

    public function __construct(MailerInterface $mailer, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    #[Route('/test-email', name: 'test_email')]
    public function sendTestEmail(): Response
    {
        $email = (new Email())
            ->from('niftynoms@niftynoms.com')
            ->to('adrienhecq@gmail.com')
            ->subject('Test Email')
            ->text('This is a test email.');

        try {
            $this->logger->info('MAILER_DSN: ' . getenv('MAILER_DSN'));
            $this->mailer->send($email);
            return new Response('Email sent successfully');
        } catch (\Exception $e) {
            $this->logger->error('Email sending failed: '.$e->getMessage());
            return new Response('Failed to send email: '.$e->getMessage(), 500);
        }
    }
}


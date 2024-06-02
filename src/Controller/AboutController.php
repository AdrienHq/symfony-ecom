<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AboutController extends AbstractController
{
    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('about/about.html.twig');
    }

    #[Route('/legals', name: 'legals')]
    public function terms(): Response
    {
        return $this->render('legals/terms.html.twig');
    }

    #[Route('/privacy-policy', name: 'privacy_policy')]
    public function privacyPolicy(): Response
    {
        return $this->render('legals/privacy_policy.html.twig');
    }

    #[Route('/cookie-policy', name: 'cookie_policy')]
    public function cookiePolicy(): Response
    {
        return $this->render('legals/cookie_policy.html.twig');
    }

    #[Route('/contact', name: 'contact')]
    public function contact(): Response
    {
        return $this->render('contact/contact.html.twig');
    }
}

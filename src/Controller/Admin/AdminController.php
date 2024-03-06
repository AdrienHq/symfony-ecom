<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class AdminController  extends AbstractController
{
    #[Route('/admin', name: "admin")]
    public function index()
    {
        return $this->render("/admin/admin.html.twig");
    }

}
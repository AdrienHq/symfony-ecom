<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class AdminController  extends AbstractController
{
    #[Route('/admin', name: "admin")]
    public function index()
    {
        return $this->render("/admin/admin.html.twig");
    }

}
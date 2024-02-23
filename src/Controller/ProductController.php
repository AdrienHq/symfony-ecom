<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    public function __construct(
        private readonly ProductRepository $productRepository
    )
    {
    }

    #[Route('/product/{slug}-{id}', name: "product.detail", requirements: ['id' => '\d+' , 'slug' => '[a-z0-9-]+'])]
    public function index(int $id, string $slug): Response
    {
        return $this->render("Products/productDetail.html.twig",[
            'id' => $id,
            'slug' => $slug,
            'product' => $this->productRepository->find($id)
        ]);
    }
}
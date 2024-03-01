<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use function PHPUnit\Framework\throwException;

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
        $product = $this->productRepository->find($id);
        if($product->getId() != $slug)
        {
            return $this->redirectToRoute("product.detail", ['slug' => $product->getSlug(), 'id' => $product->getId()]);
        }

        return $this->render("Products/productDetail.html.twig",[
            'product' => $product
        ]);
    }
}
<?php

namespace App\Controller;

use App\Entity\Products;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/products', name: 'products_')]
class ProductsController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('products/index.html.twig', [
            'controller_name' => 'ProductsController',
        ]);
    }

    // slug
    #[Route('/{slug}', name: 'details')]
    public function details(Products $product): Response
    {
        // injection de dependance en cherchant par entity 
        // dd($products->getName());
        // return $this->render('products/details.html.twig', [
            //     // 'product'=> $product
            
            // ]);
        //dd($product);

        return $this->render('products/details.html.twig', compact('product'));


  }
}

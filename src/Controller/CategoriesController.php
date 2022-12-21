<?php

namespace App\Controller;

use App\Entity\Categories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/categories', name: 'categories_')]
class CategoriesController extends AbstractController
{
    // slug
    #[Route('/{slug}', name: 'list')]
    public function list(Categories $category): Response
    {
       
        // On cherche la liste de produits par catégorie 
         $products = $category->getProducts(); 
        // return $this->render('categories/list.html.twig', compact('category', 'products'));
        // syntaxe alternative : 
        
        return $this->render('categories/list.html.twig', 
        
        [
            'category'=> $category,
            'products'=>$products
        ]
        );


  }
}

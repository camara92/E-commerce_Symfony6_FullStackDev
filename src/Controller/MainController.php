<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Route : permet de gérer les annotations dans le projet Symfony

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(CategoriesRepository $categoriesRepository, ProductsRepository $productsRepository): Response
    {
        // la repo permet d'interroger la bdd 
        // 
        return $this->render('main/index.html.twig', [
            'categories'=> $categoriesRepository->findBy([], 
            ['categoryOrder'=> 'ASC']),

            // 'produits' =>$productsRepository->findBy([], 
            // ['name'=>'ASC'])
            
            
        ]);
    }
}

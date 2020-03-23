<?php

namespace App\Controller;


use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="dashboard")
     */


    public function index(ProductRepository $productRepository)
    {


        return $this->render('dashboard/index.html.twig', [


            'username' => $this->getUser()->getNaam(),
            'products' => $productRepository->findAll(),

        ]);
    }
    /**
     * @Route("/success", name="success")
     */
    public function checkOutSuccess()
    {
        return $this->render('dashboard/success.html.twig');

    }


}

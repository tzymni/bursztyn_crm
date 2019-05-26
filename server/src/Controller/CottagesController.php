<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CottagesController extends Controller
{
    /**
     * @Route("/cottages", name="cottages")
     */
    public function index()
    {
        return $this->render('cottages/index.html.twig', [
            'controller_name' => 'CottagesController',
        ]);
    }
}

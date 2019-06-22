<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ReservationsController extends Controller
{
    /**
     * @Route("/reservations", name="reservations")
     */
    public function index()
    {
        return $this->render('reservations/index.html.twig', [
            'controller_name' => 'ReservationsController',
        ]);
    }
}

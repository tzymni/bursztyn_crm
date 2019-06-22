<?php

namespace App\Controller;

use App\Entity\Reservations;
//use App\Service\CottageService;
use App\Service\ResponseErrorDecoratorService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ReservationsController extends Controller  implements TokenAuthenticatedController {

    /**
     * @Route("/reservations", name="reservations")
     */
    public function index() {
        return $this->render('reservations/index.html.twig', [
                    'controller_name' => 'ReservationsController',
        ]);
    }

    /**
     * @Route("/api/events")
     * @Method("GET")
     * @param ResponseErrorDecoratorService $errorDecorator
     */
    public function getActiveEvents(
    ResponseErrorDecoratorService $errorDecorator
    ) {

        $events = [];
        $status = JsonResponse::HTTP_OK;
        
        $events[0]['title'] = "Rezerwacja Domek 1 dla Tomasza Lisowego";
        $events[0]['start_date'] = "2019-06-19";
        $events[0]['end_date'] = "2019-06-28";
        $events[0]['color'] = "#d4c2fc";
        
        $events[1]['title'] = "Rezerwacja Domek 2 dla Kici Lisowej";
        $events[1]['start_date'] = "2019-06-23";
        $events[1]['end_date'] = "2019-06-28";
        $events[1]['color'] = "#DC143C";
        
        
        $response = [];
        $response['results'] = $events;
        $response['total_results'] = count($events);
        

        return new JsonResponse($response, $status);
    }

}

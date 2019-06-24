<?php

namespace App\Controller;

use App\Entity\Reservations;
use App\Entity\Cottages;
//use App\Service\CottageService;
use App\Service\ReservationService;
use App\Service\ResponseErrorDecoratorService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ReservationsController extends Controller {

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
    ResponseErrorDecoratorService $errorDecorator, ReservationService $reservationService
    ) {

        $status = JsonResponse::HTTP_OK;

        $events_active = $this->getDoctrine()->getRepository(Reservations::class)->findActiveEvents();
        $events = $reservationService->prepareEventsData($events_active);

        $response = [];
        $response['results'] = $events;
        $response['total_results'] = count($events);

        return new JsonResponse($response, $status);
    }

}

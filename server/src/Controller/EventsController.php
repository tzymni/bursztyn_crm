<?php

namespace App\Controller;

use App\Entity\CottagesCleaningEvents;
use App\Entity\Events;
use App\Lib\EventDecorator;
use App\Service\EventsService;
use App\Service\ReservationService;
use App\Service\ResponseErrorDecoratorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller for handling requests related to events (getting event list by type, getting one event, etc).
 *
 * @package App\Controller
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 */
class EventsController extends AbstractController implements TokenAuthenticatedController
{
    /**
     * Get all active events by type (if it's defined).
     *
     * @Route("/event/list/type/{type}", methods={"GET"})
     * @param Request $request
     * @param ResponseErrorDecoratorService $errorDecorator
     * @param EventsService $eventsService
     * @return JsonResponse
     */
    public function getActiveEvents(
        Request $request,
        ResponseErrorDecoratorService $errorDecorator,
        EventsService $eventsService
    ): JsonResponse {
        try {

            $type = $request->get('type');
            $events = $eventsService->getActiveEvents($type);
            $response = array();

            if (!empty($events)) {
                foreach ($events as $event) {
                    $tmp = array();
                    $tmp['id'] = $event->getId();
                    $tmp['title'] = $event->getTitle();
                    $tmp['date_from'] = $event->getDateFromUnixUtc();
                    $tmp['date_to'] = $event->getDateToUnixUtc();
                    $tmp['type'] = $event->getType();
                    $reservation = $event->getReservations();

                    if (!empty($reservation)) {
                        $cottage = $reservation->getCottage();
                        $tmp['color'] = $cottage->getColor();
                        $tmp['cottage_id'] = $cottage->getId();
                    }

                    if ($event->getType() == CottagesCleaningEvents::EVENT_TYPE) {
                        $tmp['color'] = CottagesCleaningEvents::EVENT_COLOR;
                    }

                    $response[] = $tmp;
                }
            }
            $status = JsonResponse::HTTP_OK;
        } catch (\Exception $exception) {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $response = $errorDecorator->decorateError($status, $exception->getMessage());
        }
        return new JsonResponse($response, $status);
    }

    /**
     * Get event by id and type.
     *
     * @Route("/event/{id}/type/{type}", methods={"GET"})
     * @param Request $request
     * @param EventsService $eventsService
     * @param ReservationService $reservationService
     * @param ResponseErrorDecoratorService $errorDecoratorService
     * @return JsonResponse
     */
    public function getEventByTypeAndId(
        Request $request,
        EventsService $eventsService,
        ReservationService $reservationService,
        ResponseErrorDecoratorService $errorDecoratorService

    ): JsonResponse {
        $id = $request->get('id');
        $type = $request->get('type');

        $event = $eventsService->getActiveEventById($id);

        if ($event instanceof Events) {
            $eventDecorator = new EventDecorator($event);
            $eventType = null;
            switch ($type) {
                case EventsService::RESERVATION_EVENT:
                    $eventType = $reservationService;
                    break;

                case EventsService::CLEANING_EVENT:
                    $eventType = $eventsService;
                    break;
            }

            $event = $eventDecorator->decorateEvent($eventType);

            $status = JsonResponse::HTTP_OK;
            $result = $event;
        } else {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $result = $errorDecoratorService->decorateError($status, $event);
        }

        return new JsonResponse($result, $status);
    }
}

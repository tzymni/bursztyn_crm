<?php

namespace App\Controller;

use App\Entity\CottagesCleaningEvents;
use App\Entity\Events;
use App\Lib\EventDecorator;
use App\Service\EventsService;
use App\Service\ReservationService;
use App\Service\ResponseErrorDecoratorService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EventsController
 * @package App\Controller
 *
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 */
class EventsController extends AbstractController implements TokenAuthenticatedController
{

    /**
     * Create a new event.
     *
     * @Route("/event/reservation", methods={"POST"})
     * @param Request $request
     * @param EventsService $eventsService
     * @param ResponseErrorDecoratorService $errorDecoratorService
     * @return JsonResponse
     */
    public function addReservationEvent(
        Request $request,
        EventsService $eventsService,
        ResponseErrorDecoratorService $errorDecoratorService
    ) {
        $body = $request->getContent();
        $data = json_decode($body, true);

        $dataDateEmpty = empty($data['date_from']) || empty($data['date_to']);
        $dataGuestsEmpty = empty($data['guest_first_name']) || empty($data['guest_last_name']);
        $dataRelationParamsEmpty = empty($data['user_id']) || empty($data['cottage_id']);

        if (is_null($data) || $dataDateEmpty || $dataGuestsEmpty || $dataRelationParamsEmpty) {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $data = $errorDecoratorService->decorateError(
                $status,
                "Invalid data!"
            );

            return new JsonResponse($data, $status);
        }

        if (empty($result)) {
            $result = $eventsService->createEvent($data);
        }

        if ($result instanceof Events) {
            $status = JsonResponse::HTTP_OK;
            $data = array();
        } else {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $data = $errorDecoratorService->decorateError($status, $result);
        }

        return new JsonResponse($data, $status);
    }

    /**
     * @Route("/event/list", methods={"GET"})
     * @param ResponseErrorDecoratorService $errorDecorator
     */
    public function getEventList(
        ResponseErrorDecoratorService $errorDecorator,
        EventsService $eventsService
    ) {
        try {
            $events = $eventsService->getActiveEvents();

            if (!empty($events)) {
                $responseData = array();
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
                    }

                    if ($event->getType() == CottagesCleaningEvents::EVENT_TYPE) {
                        $tmp['color'] = '#e5e5e5';
                    }

                    $responseData[] = $tmp;
                }
            }
            $status = JsonResponse::HTTP_OK;
        } catch (\Exception $exception) {
            $status = JsonResponse::HTTP_OK;
            $responseData = $exception->getMessage();
        }
        return new JsonResponse($responseData, $status);
    }

    /**
     * @Route("/event/reservation/{id}", methods={"PUT"})
     */
    public function updateReservationEvent(
        Request $request,
        EventsService $eventsService,
        ResponseErrorDecoratorService $errorDecoratorService
    ) {
        $id = $request->get('id');
        $body = $request->getContent();
        $data = json_decode($body, true);

        $dataDateEmpty = empty($data['date_from']) || empty($data['date_to']);
        $dataGuestsEmpty = empty($data['guest_first_name']) || empty($data['guest_last_name']);
        $dataRelationParamsEmpty = empty($data['user_id']) || empty($data['cottage_id']);

        if (is_null($data) || $dataDateEmpty || $dataGuestsEmpty || $dataRelationParamsEmpty) {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $data = $errorDecoratorService->decorateError(
                $status,
                "Invalid data!"
            );

            return new JsonResponse($data, $status);
        }

        $event = $eventsService->getActiveEventById($id);

        if ($event instanceof Events) {
            $result = $eventsService->createEvent($data, $event);
        } else {
            $result = $event;
        }

        if ($result instanceof Events) {
            $status = JsonResponse::HTTP_OK;
            $data = array();
        } else {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $data = $errorDecoratorService->decorateError($status, $result);
        }

        return new JsonResponse($data, $status);
    }

    /**
     * @Route("/event/{id}/type/{type}", methods={"GET"})
     * @param ResponseErrorDecoratorService $errorDecorator
     */
    public function getEventByTypeAndId(
        Request $request,
        EventsService $eventsService,
        ReservationService $reservationService,
        ResponseErrorDecoratorService $errorDecoratorService

    ) {
        $id = $request->get('id');
        $type = $request->get('type');

        $event = $eventsService->getActiveEventById($id);

        $result = array();
        if ($event instanceof Events) {
            $eventDecorator = new EventDecorator($event);
            $eventType = null;
            switch ($type) {
                case EventsService::RESERVATION_EVENT:
                    $eventType = $reservationService;
                    break;
            }

            $event = $eventDecorator->decorateEvent($eventType);

            $result = $event;
        } else {
            $result = $event;
        }

        return new JsonResponse($result, JsonResponse::HTTP_OK);
    }
}

<?php

namespace App\Controller;

use App\Entity\Events;
use App\Service\CottagesCleaningEventsService;
use App\Service\EventsService;
use App\Service\ReservationService;
use App\Service\ResponseErrorDecoratorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CleaningEventsController extends AbstractController
{
    /**
     * @Route("/cleaning/events", name="cleaning_events")
     */
    public function index()
    {
        return $this->render('cleaning_events/index.html.twig', [
            'controller_name' => 'CleaningEventsController',
        ]);
    }

    /**
     * @Route ("/cleaning/{id}", methods={"GET"})
     * @param Request $request
     * @param EventsService $eventsService
     * @param ResponseErrorDecoratorService $errorDecorator
     * @return JsonResponse
     */
    public function getCleaningEvent(
        Request $request,
        EventsService $eventsService,
        ResponseErrorDecoratorService $errorDecorator
    ) {

        $id = $request->get('id');

        if (!empty($id)) {
            $eventResponse = $eventsService->getActiveEventById($id);
        } else {
            $eventResponse = 'Id not found!';
        }
        if ($eventResponse instanceof Events) {
            $status = JsonResponse::HTTP_OK;
            $data = [
                'id' => $eventResponse->getId(),
                'name' => $eventResponse->getTitle(),
                'date' => $eventResponse->getDateFrom(),
            ];
        } else {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $data = $errorDecorator->decorateError($status, $eventResponse);
        }

        return new JsonResponse($data, $status);
    }

    /**
     * @Route ("/cleaning/details/{id}", methods={"GET"})
     * @param Request $request
     * @param EventsService $eventsService
     * @param ResponseErrorDecoratorService $responseErrorDecoratorService
     */
    public function getCleaningEventDetails(
        Request $request,
        EventsService $eventsService,
        CottagesCleaningEventsService $cottagesCleaningEventsService,
        ReservationService $reservationService,
        ResponseErrorDecoratorService $errorDecorator
    ) {
        $id = $request->get('id');

        try {

            $event = $eventsService->getActiveEventById($id);
            $cottageCleaningEvents = $cottagesCleaningEventsService->getCottageCleaningEventsByEvent($event);

            $details = array();
            foreach ($cottageCleaningEvents as $cottageCleaningEvent) {
                $tmp = array();
                $tmp['cottage_id'] = $cottageCleaningEvent->getCottage()->getId();
                $tmp['cottage_name'] = $cottageCleaningEvent->getCottage()->getName();
                $nextReservation = $reservationService->getNextActiveReservationByCottage($cottageCleaningEvent->getCottage(),
                    $event->getDateTo());
                $periodInDays = 0;
                if (!empty($nextReservation)) {
                    $nextReservationDateFrom = $nextReservation['date_from'];
                    $nextReservationDateTo = $nextReservation['date_to'];
                    $dateDiff = strtotime($nextReservationDateTo) - strtotime($nextReservationDateFrom);

                    $periodInDays = round($dateDiff / (60 * 60 * 24));
                }
                $tmp['next_reservation_date'] = !empty($nextReservation) ? $nextReservation['date_from'] : 'Brak';
                $tmp['next_reservation_period'] = $periodInDays;
                $tmp['next_reservation_event_id'] = !empty($nextReservation) ? $nextReservation['event_id'] : null;
                $tmp['next_reservation_id'] = !empty($nextReservation) ? $nextReservation['reservation_id'] : null;

                $details[] = $tmp;
            }
            $status = JsonResponse::HTTP_OK;
            return new JsonResponse($details, $status);
        }
        catch (\Exception $exception) {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $data = $errorDecorator->decorateError($status, 'Something goes wrong!');
            return new JsonResponse($data, $status);
        }

        print_r($details);
//        echo $cottageCleaning[0]['id'];

        die();
    }
}

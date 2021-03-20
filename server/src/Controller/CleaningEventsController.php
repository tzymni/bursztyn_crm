<?php

namespace App\Controller;

use App\Entity\CottagesCleaningEvents;
use App\Service\CottagesCleaningEventsService;
use App\Service\EventsService;
use App\Service\ResponseErrorDecoratorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller for handling requests related to cleaning events.
 *
 * @package App\Controller
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 */
class CleaningEventsController extends AbstractController implements TokenAuthenticatedController
{
    /**
     * @Route ("/cleaning/details/{id}", methods={"GET"})
     * @param Request $request
     * @param CottagesCleaningEventsService $cottagesCleaningEventsService
     * @param ResponseErrorDecoratorService $errorDecorator
     * @return JsonResponse
     */
    public function getCleaningEventDetails(
        Request $request,
        CottagesCleaningEventsService $cottagesCleaningEventsService,
        ResponseErrorDecoratorService $errorDecorator
    ): JsonResponse {
        $id = $request->get('id');

        try {
            $details = $cottagesCleaningEventsService->generateCottageCleaningEventDetails($id);
            $status = JsonResponse::HTTP_OK;
            return new JsonResponse($details, $status);
        } catch (\Exception $exception) {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $data = $errorDecorator->decorateError($status, 'Something goes wrong!');
            return new JsonResponse($data, $status);
        }

    }

    /**
     * Get all cleaning events without grouping per cottage.
     *
     * @Route ("/cleaning/list/all", methods={"GET"})
     * @param CottagesCleaningEventsService $cottagesCleaningEventsService
     * @param ResponseErrorDecoratorService $errorDecorator
     * @return JsonResponse
     */
    public function getAllCleaningEventsWithoutGrouping(
        CottagesCleaningEventsService $cottagesCleaningEventsService,
        ResponseErrorDecoratorService $errorDecorator
    ): JsonResponse {

        try {
            $cleaningEvents = $cottagesCleaningEventsService->getAllCottagesCleaningEvents();
            $events = array();

            $x = 0;
            foreach ($cleaningEvents as $cleaningEvent) {
                $events[$x]['cottage_id'] = $cleaningEvent->getCottage()->getId();
                $events[$x]['cottage_color'] = $cleaningEvent->getCottage()->getColor();
                $events[$x]['event_id'] = $cleaningEvent->getEvent()->getId();
                $events[$x]['event_type'] = $cleaningEvent->getEvent()->getType();
                $events[$x]['id'] = $cleaningEvent->getId();
                $events[$x]['date_from'] = $cleaningEvent->getEvent()->getDateFromUnixUtc();
                $events[$x]['date_to'] = strtotime('+8 hours', $cleaningEvent->getEvent()->getDateToUnixUtc());
                $events[$x]['title'] = 'Sprzątanie';
                $x++;
            }

            $status = JsonResponse::HTTP_OK;
            return new JsonResponse($events, $status);

        } catch (\Exception $exception) {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $data = $errorDecorator->decorateError($status, 'Something goes wrong!');
            return new JsonResponse($data, $status);
        }
    }

    /**
     * @Route ("/next-cleanings", methods={"GET"})
     *
     * @param EventsService $eventsService
     * @param CottagesCleaningEventsService $cottagesCleaningEventsService
     * @param ResponseErrorDecoratorService $errorDecorator
     * @return JsonResponse
     */
    public function getNextCleaningEventsWithDetails(
        EventsService $eventsService,
        CottagesCleaningEventsService $cottagesCleaningEventsService,
        ResponseErrorDecoratorService $errorDecorator
    ): JsonResponse {
        try {
            $cleaningEvents = $eventsService->getAllFutureActiveEventsByType(CottagesCleaningEvents::EVENT_TYPE);
            $response = array();

            $x = 0;
            foreach ($cleaningEvents as $cleanEvent) {
                $details = $cottagesCleaningEventsService->generateCottageCleaningEventDetails($cleanEvent->getId());
                $response[$x]['details'] = $details;
                $response[$x]['number_of_cottages'] = count($details);
                $response[$x]['title'] = $cleanEvent->getTitle();
                $response[$x]['date_from'] = substr($cleanEvent->getDateFrom(), 0, 10);
                $x++;
            }

            $status = JsonResponse::HTTP_OK;

        } catch (\Exception $exception) {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $response = $errorDecorator->decorateError($status, 'Something goes wrong!');
        }
        return new JsonResponse($response, $status);

    }

}

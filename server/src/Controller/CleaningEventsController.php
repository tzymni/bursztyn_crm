<?php

namespace App\Controller;

use App\Entity\Events;
use App\Service\EventsService;
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

}

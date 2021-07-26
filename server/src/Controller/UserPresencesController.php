<?php

namespace App\Controller;

use App\Entity\Events;
use App\Entity\UserPresences;
use App\Lib\UserPresenceCreator;
use App\Service\EventsService;
use App\Service\ResponseErrorDecoratorService;
use App\Service\UserPresenceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserPresencesController
 *
 * @package App\Controller
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 */
class UserPresencesController extends AbstractController implements TokenAuthenticatedController
{

    /**
     * Creates new user by given data
     *
     * @Route("/user_presence/{id}", methods={"PUT"})
     * @param Request $request
     * @param ResponseErrorDecoratorService $errorDecorator
     * @param EventsService $eventsService
     * @param UserPresenceService $userPresenceService
     * @return JsonResponse
     */
    public function updateUserPresence(
        Request $request,
        ResponseErrorDecoratorService $errorDecorator,
        EventsService $eventsService,
        UserPresenceService $userPresenceService
    ): JsonResponse {

        $body = $request->getContent();
        $data = json_decode($body, true);

        $dataIsCorrect = !is_null($data) && !empty($data['user_id']) && !empty($data['date_from']) && !empty($data['date_to']) && !empty($data['created_by_id']);
        if (!$dataIsCorrect && !empty($data['id'])) {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $responseData = $errorDecorator->decorateError(
                $status,
                "Invalid data!"
            );

            return new JsonResponse($responseData, $status);
        }

        try {
            $event = $eventsService->getActiveEventById($data['id']);
            $data['event'] = $event;
            $event instanceof Events;
            $data['user_presence'] = $userPresenceService->getUserPresenceByEvent($event);
            $eventsService->createEvent(new UserPresenceCreator($eventsService->em), $data);
            $responseData = 'OK';
            $status = JsonResponse::HTTP_OK;
        } catch (\Exception $exception) {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $responseData = $errorDecorator->decorateError($status, $exception->getMessage());
        }

        return new JsonResponse($responseData, $status);

    }

    /**
     * Creates new user by given data
     *
     * @Route("/user_presence", methods={"POST"})
     * @param Request $request
     * @param EventsService $eventsService
     * @param ResponseErrorDecoratorService $errorDecorator
     * @return JsonResponse
     */
    public function createUserPresence(
        Request $request,
        EventsService $eventsService,
        ResponseErrorDecoratorService $errorDecorator
    ): JsonResponse {

        $body = $request->getContent();
        $data = json_decode($body, true);

        $dataIsCorrect = !is_null($data) && !empty($data['user_id']) && !empty($data['date_from']) && !empty($data['date_to']) && !empty($data['created_by_id']);
        if (!$dataIsCorrect) {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $responseData = $errorDecorator->decorateError(
                $status,
                "Invalid data!"
            );

            return new JsonResponse($responseData, $status);
        }

        try {
            $eventsService->createEvent(new UserPresenceCreator($eventsService->em), $data);
            $responseData = 'OK';
            $status = JsonResponse::HTTP_OK;
        } catch (\Exception $exception) {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $responseData = $errorDecorator->decorateError($status, $exception->getMessage());
        }

        return new JsonResponse($responseData, $status);

    }

    /**
     * Get user by id.
     *
     * @Route("/user_presence/cleaning/{cleaning_id}", methods={"GET"})
     * @param Request $request
     * @param EventsService $eventsService
     * @param ResponseErrorDecoratorService $errorDecorator
     * @return JsonResponse
     */
    public function getUserPresenceByCleaningEvent(
        Request $request,
        EventsService $eventsService,
        ResponseErrorDecoratorService $errorDecorator
    ): JsonResponse {

        $cleaningEventId = $request->get('cleaning_id');

        try {

            $cleaningEvent = $eventsService->getActiveEventById($cleaningEventId);
            $cleaningStartDate = $cleaningEvent->getDateFrom();
            $presenceEvents = $eventsService->getActiveEventsBetweenDate(UserPresences::EVENT_TYPE, $cleaningStartDate);

            $data = array();
            $x = 0;

            if (!empty($presenceEvents)) {

                foreach ($presenceEvents as $presenceEvent) {
                    $data[$x]['id'] = $presenceEvent->getId();
                    $data[$x]['title'] = $presenceEvent->getTitle();
                    $x++;
                }

            }
            $responseData['data'] = $data;
            $responseData['status'] = 'OK';
            $status = JsonResponse::HTTP_OK;

        } catch (\Exception $exception) {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $responseData = $errorDecorator->decorateError($status, $exception->getMessage());
        }

        return new JsonResponse($responseData, $status);
    }

}
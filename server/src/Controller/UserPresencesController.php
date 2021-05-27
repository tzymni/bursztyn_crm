<?php

namespace App\Controller;

use App\Lib\UserPresenceCreator;
use App\Service\EventsService;
use App\Service\ResponseErrorDecoratorService;
use App\Service\UserPresenceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserPresencesController extends AbstractController
{

    /**
     * Creates new user by given data
     *
     * @Route("/user_presence", methods={"POST"})
     * @param Request $request
     * @param UserPresenceService $presenceService
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

}
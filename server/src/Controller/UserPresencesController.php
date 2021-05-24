<?php

namespace App\Controller;

use App\Entity\CottagesCleaningEvents;
use App\Lib\UserPresenceCreator;
use App\Service\CottagesCleaningEventsService;
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
        print_r($data);
        $dataIsCorrect = !is_null($data) && !empty($data['user_id']) && !empty($data['date_from']) && !empty($data['date_to']);
        if (!$dataIsCorrect) {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $response = $errorDecorator->decorateError(
                $status,
                "Invalid data!"
            );
        }

        echo "DUPA";

        try {
            $eventsService->createEvent(new UserPresenceCreator($eventsService->em), $data);

        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }

        die();

//

//

//
//        $user = $userPresenceService->createUserPresence($data);
//
//        if ($user instanceof UserPresences && empty($response)) {
//            $status = JsonResponse::HTTP_OK;
//            $response = array();
//        } else {
//            $errorResponse = $user;
//            $status = JsonResponse::HTTP_BAD_REQUEST;
//            $response = $errorDecorator->decorateError($status, $errorResponse);
//        }
//
//        return new JsonResponse($response, $status);
    }

}
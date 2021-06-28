<?php

namespace App\Controller;

use App\Entity\Cottages;
use App\Entity\Events;
use App\Entity\Reservations;
use App\Entity\UserPresences;
use App\Lib\UserPresenceCreator;
use App\Service\CottageService;
use App\Service\EventsService;
use App\Service\ReservationService;
use App\Service\ResponseErrorDecoratorService;
use App\Service\UserPresenceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PublicController
 *
 * @package App\Controller
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 */
class PublicController extends AbstractController
{

    /**
     * Check if cottages are available between defined dates and return only available cottages (id and name).
     *
     * @Route ("/api/reservation/{date_from}/{date_to}", methods={"GET"})
     *
     * @param ResponseErrorDecoratorService $errorDecorator
     * @param Request $request
     * @param ReservationService $reservationService
     * @param CottageService $cottageService
     * @return JsonResponse
     * @throws \Exception
     */
    public function checkCottagesAvailabilityForReservation(
        ResponseErrorDecoratorService $errorDecorator,
        Request $request,
        ReservationService $reservationService,
        CottageService $cottageService
    ): JsonResponse {

        date_default_timezone_set('UTC');

        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $response = array();

        if ($dateFrom == $dateTo) {
            $response['error'] = 'Dates of reservation must be different!';
        }

        if (\DateTime::createFromFormat('Y-m-d', $dateFrom) == false) {
            $response['error'] = 'Wrong date from!';
        }

        if (\DateTime::createFromFormat('Y-m-d', $dateTo) == false) {
            $response['error'] = 'Wrong date to!';
        }

        if (strtotime($dateFrom) < time()) {
            $response['error'] = 'Wrong date from!';
        }

        if (!empty($response['error'])) {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $response = $errorDecorator->decorateError($status, $response['error']);
            return new JsonResponse($response, $status);
        }

        $availableCottages = array();

        $cottages = $cottageService->getActiveCottages();

        $dateFromUnix = strtotime($dateFrom) + Reservations::HOUR_OF_END * 3600;
        $dateToUnix = strtotime($dateTo) + Reservations::HOUR_OF_START * 3600;

        if (!empty($cottages)) {

            $x = 0;
            foreach ($cottages as $cottage) {
                if ($cottage instanceof Cottages) {
                    if ($reservationService->checkCottageAvailability($cottage->getId(), $dateFromUnix, $dateToUnix)) {
                        $availableCottages[$x]['name'] = $cottage->getName();
                        $x++;
                    }
                }
            }

            $response['number_of_free_cottages'] = count($availableCottages);
            $response['request_details']['date_from'] = $dateFrom;
            $response['request_details']['date_to'] = $dateTo;

            $status = JsonResponse::HTTP_OK;
        } else {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $response['error'] = 'No cottages';

        }
        return new JsonResponse($response, $status);

    }

}
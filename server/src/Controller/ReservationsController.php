<?php

namespace App\Controller;

use App\Entity\Cottages;
use App\Entity\Reservations;
use App\Service\CottageService;
use App\Service\ReservationService;
use App\Service\ResponseErrorDecoratorService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller for handling requests related to reservation events.
 *
 * @package App\Controller
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 */
class ReservationsController extends AbstractController
{

    /**
     * Get list of cottages and list of all reservations for them.
     *
     * @Route("/reservation/list/groupBy/cottages", methods={"GET"})
     * @param ResponseErrorDecoratorService $errorDecorator
     * @param CottageService $cottageService
     * @return JsonResponse
     */
    public function getReservationListGroupedByCottages(
        ResponseErrorDecoratorService $errorDecorator,
        CottageService $cottageService
    ): JsonResponse {
        try {

            $cottages = $cottageService->getActiveCottages();

            $responseData = array();

            foreach ($cottages as $cottage) {
                $tmp = array();

                $tmp['id'] = $cottage->getId();
                $tmp['name'] = $cottage->getName();

                $reservations = $cottage->getReservations();
                $tmpReservations = null;

                if (!empty($reservations)) {
                    foreach ($reservations as $reservation) {
                        $tmpReservations = array();
                        $tmpReservations['guest_first_name'] = $reservation->getGuestFirstName();
                        $tmpReservations['guest_last_name'] = $reservation->getGuestLastName();
                        $tmpReservations['guest_phone'] = $reservation->getGuestPhoneNumber();
                        $tmpReservations['guests_number'] = $reservation->getGuestsNumber();
                        $tmpReservations['advance_payment'] = $reservation->getAdvancePayment();
                        $tmpReservations['extra_info'] = $reservation->getExtraInfo();

                        $event = $reservation->getEvent();

                        $tmpReservations['event']['id'] = $event->getId();
                        $tmpReservations['event']['date_from'] = $event->getDateFrom();
                        $tmpReservations['event']['date_to'] = $event->getDateTo();
                        $tmpReservations['event']['date_from_unix'] = $event->getDateFromUnixUtc();
                        $tmpReservations['event']['date_to_unix'] = $event->getDateToUnixUtc();

                        $tmp['reservations'][] = $tmpReservations;
                    }
                }

                $responseData['cottages'][] = $tmp;
            }

            $status = JsonResponse::HTTP_OK;
        } catch (Exception $exception) {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $responseData = $errorDecorator->decorateError($status, $exception->getMessage());
        }
        return new JsonResponse($responseData, $status);
    }

    /**
     * Check if defined cottages are available between defined dates and return only available cottages (id and name).
     *
     * @Route ("/reservation/availability/cottage_ids/{cottage_ids}/date_from/{date_from}/date_to/{date_to}", methods={"GET"})
     * @param ResponseErrorDecoratorService $errorDecorator
     * @param Request $request
     * @param ReservationService $reservationService
     * @param CottageService $cottageService
     * @return JsonResponse
     * @throws Exception
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
        $cottageIds = $request->get('cottage_ids');

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

        if (strlen($cottageIds) == 0 || strlen($cottageIds) >= 50) {
            $response['error'] = 'Wrong long of string for cottages!';
        }

        if (!empty($response['error'])) {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $response = $errorDecorator->decorateError($status, $response['error']);
            return new JsonResponse($response, $status);
        }

        $availableCottages = array();

        $dateFromUnix = strtotime($dateFrom) + Reservations::HOUR_OF_END * 3600;
        $dateToUnix = strtotime($dateTo) + Reservations::HOUR_OF_START * 3600;

        if (!empty($cottageIds)) {

            $cottages = explode(",", $cottageIds);
            $x = 0;
            foreach ($cottages as $cottageId) {

                $cottage = $cottageService->getActiveCottageById($cottageId);

                if ($cottage instanceof Cottages) {

                    if ($reservationService->checkCottageAvailability($cottageId, $dateFromUnix, $dateToUnix)) {
                        $availableCottages[$x]['name'] = $cottage->getName();
                        $availableCottages[$x]['id'] = $cottage->getId();
                        $x++;
                    }

                }
            }

            $response['available_cottages'] = $availableCottages;
            $response['request_details']['cottages_ids'] = $cottageIds;
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
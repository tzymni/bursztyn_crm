<?php

namespace App\Controller;

use App\Entity\Events;
use App\Lib\EventDecorator;
use App\Service\CottageService;
use App\Service\EventsService;
use App\Service\ReservationService;
use App\Service\ResponseErrorDecoratorService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ReservationsController
 * @package App\Controller
 *
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 */
class ReservationsController extends AbstractController implements TokenAuthenticatedController
{

    /**
     * @Route("/reservation/list/groupBy/cottages")
     * @Method("GET")
     * @param ResponseErrorDecoratorService $errorDecorator
     * @return JsonResponse
     */
    public function getReservationListGroupedByCottages(
        ResponseErrorDecoratorService $errorDecorator,
        CottageService $cottageService
    ) {
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
                    foreach ($reservations as $reser) {
                        $tmpReservations = array();
                        $tmpReservations['guest_first_name'] = $reser->getGuestFirstName();
                        $tmpReservations['guest_last_name'] = $reser->getGuestLastName();
                        $tmpReservations['guest_phone'] = $reser->getGuestPhoneNumber();
                        $tmpReservations['guests_number'] = $reser->getGuestsNumber();
                        $tmpReservations['advance_payment'] = $reser->getAdvancePayment();
                        $tmpReservations['extra_info'] = $reser->getExtraInfo();

                        $event = $reser->getEvent();

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
        } catch (\Exception $exception) {
            $status = JsonResponse::HTTP_OK;
            $responseData = $exception->getMessage();
        }
        return new JsonResponse($responseData, $status);
    }

}
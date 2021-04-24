<?php

namespace App\Controller;

use App\Entity\Cottages;
use App\Service\CottageService;
use App\Service\ResponseErrorDecoratorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller for handling requests related to Cottages table.
 * @package App\Controller
 *
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 */
class CottagesController extends AbstractController implements TokenAuthenticatedController
{

    /**
     * Get all active cottages.
     *
     * @Route("/cottage/list", methods={"GET"})
     * @param CottageService $cottageService
     * @param ResponseErrorDecoratorService $errorDecorator
     * @return JsonResponse
     */
    public function getCottagesList(
        CottageService $cottageService,
        ResponseErrorDecoratorService $errorDecorator
    ): JsonResponse {
        try {
            $cottages = $cottageService->getActiveCottages();

            $response = array();
            $x = 0;

            if (current($cottages) instanceof Cottages) {

                foreach ($cottages as $cottage) {

                    $response[$x]['id'] = $cottage->getId();
                    $response[$x]['color'] = $cottage->getColor();
                    $response[$x]['name'] = $cottage->getName();
                    $response[$x]['extra_info'] = $cottage->getExtraInfo();
                    $response[$x]['is_active'] = $cottage->getIsActive();

                    $x++;
                }

            }
            $status = JsonResponse::HTTP_OK;
        } catch (\Exception $exception) {
            $status = JsonResponse::HTTP_NOT_FOUND;
            $response = $errorDecorator->decorateError($status, $exception->getMessage());
        }

        return new JsonResponse($response, $status);
    }

    /**
     * Get cottage by id.
     *
     * @Route("/cottage/{id}", methods={"GET"})
     * @param Request $request
     * @param CottageService $cottageService
     * @param ResponseErrorDecoratorService $errorDecorator
     * @return JsonResponse
     */
    public function getCottage(
        Request $request,
        CottageService $cottageService,
        ResponseErrorDecoratorService $errorDecorator
    ): JsonResponse {
        $id = $request->get('id');

        if (!empty($id)) {
            $cottageResponse = $cottageService->getActiveCottageById($id);
        } else {
            $cottageResponse = null;
        }

        if ($cottageResponse instanceof Cottages) {
            $status = JsonResponse::HTTP_OK;
            $data = [
                'id' => $cottageResponse->getId(),
                'name' => $cottageResponse->getName(),
                'color' => $cottageResponse->getColor(),
                'extra_info' => $cottageResponse->getExtraInfo(),
                'max_guests_number' => $cottageResponse->getMaxGuestsNumber()
            ];
        } else {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $data = $errorDecorator->decorateError($status, $cottageResponse);
        }

        return new JsonResponse($data, $status);
    }

    /**
     * Update cottage.
     *
     * @Route("/cottage/{id}", methods={"PUT"})
     * @param Request $request
     * @param CottageService $cottageService
     * @param ResponseErrorDecoratorService $errorDecorator
     * @return JsonResponse
     */
    public function updateCottage(
        Request $request,
        CottageService $cottageService,
        ResponseErrorDecoratorService $errorDecorator
    ): JsonResponse {
        $body = $request->getContent();
        $data = json_decode($body, true);
        $id = $request->get('id');

        if (is_null($data) || empty($data['name']) || empty($data['color'])) {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $data = $errorDecorator->decorateError(
                JsonResponse::HTTP_BAD_REQUEST,
                "Invalid data!"
            );

            return new JsonResponse($data, $status);
        }

        $cottage = $cottageService->getActiveCottageById($id);

        $result = $cottageService->updateCottage($cottage, $data);
        if ($result instanceof Cottages) {
            $status = JsonResponse::HTTP_OK;
            $data = array();
        } else {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $data = $errorDecorator->decorateError($status, $result);
        }

        return new JsonResponse($data, $status);
    }

}

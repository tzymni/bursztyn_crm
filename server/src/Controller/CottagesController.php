<?php

namespace App\Controller;

use App\Entity\Cottages;
use App\Service\CottageService;
use App\Service\ResponseErrorDecoratorService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CottagesController
 * @package App\Controller
 *
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 */
class CottagesController extends AbstractController implements TokenAuthenticatedController
{

    /**
     * Creates new user by given data
     *
     * @Route("/cottage/add", methods={"POST"})
     * @param Request $request
     * @param CottageService $cottageService
     * @param ResponseErrorDecoratorService $errorDecorator
     * @return JsonResponse
     */
    public function addCottage(
        Request $request,
        CottageService $cottageService,
        ResponseErrorDecoratorService $errorDecorator
    ) {
        $body = $request->getContent();
        $data = json_decode($body, true);

        if (is_null($data) || !isset($data['name']) || !isset($data['color'])) {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $data = $errorDecorator->decorateError(
                $status,
                "Invalid data!"
            );

            return new JsonResponse($data, $status);
        }

        $result = $cottageService->createCottage($data);

        if ($result instanceof Cottages) {
            $status = JsonResponse::HTTP_OK;
            $data = array();
        } else {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $data = $errorDecorator->decorateError($status, $result);
        }

        return new JsonResponse($data, $status);
    }

    /**
     * @Route("/cottage/list", methods={"GET"})
     * @param ResponseErrorDecoratorService $errorDecorator
     */
    public function getCottagesList(
        ResponseErrorDecoratorService $errorDecorator
    ) {
        try {
            $cottagesResponse = $this->getDoctrine()->getRepository(Cottages::class)->findAllActive();
            $status = JsonResponse::HTTP_OK;
        } catch (\Exception $exception) {
            $status = JsonResponse::HTTP_NOT_FOUND;
            $cottagesResponse = $exception->getMessage();
        }

        return new JsonResponse($cottagesResponse, $status);
    }

    /**
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
    ) {
        $id = $request->get('id');

        if (!empty($id)) {
            $cottageResponse = $cottageService->getActiveCottageById($id);
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
     * @Route("/cottage/{id}", methods={"PUT"})
     */
    public function updateCottage(
        Request $request,
        CottageService $cottageService,
        ResponseErrorDecoratorService $errorDecorator
    ) {
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

    /**
     * @Route("/cottage/{id}", methods={"DELETE"})
     * @param Request $request
     */
    public function deleteCottage(
        Request $request,
        CottageService $cottageService,
        ResponseErrorDecoratorService $errorDecorator
    ) {
        $id = $request->get('id');
        $deletedCottageResponse = null;

        if (!empty($id)) {
            $cottage = $cottageService->getActiveCottageById($id);

            if ($cottage instanceof Cottages) {
                $deletedCottageResponse = $cottageService->deleteCottage($cottage);
            } else {
                $deletedCottageResponse = $cottage;
            }
        }

        if ($deletedCottageResponse instanceof Cottages) {
            $status = JsonResponse::HTTP_OK;
            $data = array();
        } else {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $data = $errorDecorator->decorateError($status, $deletedCottageResponse);
        }

        return new JsonResponse($data, $status);
    }

}

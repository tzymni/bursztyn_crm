<?php

namespace App\Controller;



use App\Entity\Cottages;
use App\Service\CottageService;
use App\Service\ResponseErrorDecoratorService;
use http\Exception\InvalidArgumentException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CottagesController extends AbstractController implements TokenAuthenticatedController  {



    /**
     * Creates new user by given data
     *
     * @Route("/cottage/new")
     * @Method("POST")
     * @param Request $request
     * @param CottageService $cottageService
     * @param ResponseErrorDecoratorService $errorDecorator
     * @return JsonResponse
     */
    public function addCottage(Request $request, CottageService $cottageService, ResponseErrorDecoratorService $errorDecorator) {
        $body = $request->getContent();
        $data = json_decode($body, true);



        if (is_null($data) || !isset($data['name']) || !isset($data['color'])) {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $data = $errorDecorator->decorateError(
                    JsonResponse::HTTP_BAD_REQUEST, "Test"
            );

            return new JsonResponse($data, $status);
        }

        $result = $cottageService->createCottage($data);


        if ($result instanceof Cottages) {
            $status = JsonResponse::HTTP_CREATED;
            $data = [
                'data' => [
                    'id' => $result->getId()
                ]
            ];
        } else {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $data = $errorDecorator->decorateError($status, $result);
        }

        return new JsonResponse($data, $status);
    }

    /**
     * @Route("/api/cottages")
     * @Method("GET")
     * @param ResponseErrorDecoratorService $errorDecorator
     */
    public function getCottagesList(
    ResponseErrorDecoratorService $errorDecorator
    ) {

        try {
            $users = $this->getDoctrine()->getRepository(Cottages::class)->findAllActive();
            $status = JsonResponse::HTTP_OK;
            throw new \Exception("TEST");
        }
        catch (\Exception $exception) {
           $status = JsonResponse::HTTP_NOT_FOUND;
            $users = null;

        }

        return new JsonResponse($users, $status);
    }

        /**
     * @Route("/api/cottage/{id}")
     * @Method("GET")
     * @param ResponseErrorDecoratorService $errorDecorator
     */
    public function getCottage(Request $request, CottageService $cottageService, ResponseErrorDecoratorService $errorDecorator) {
        $id = $request->get('id');


        if (!empty($id)) {
            $cottage = $cottageService->getCottage($id);
        }



        if ($cottage instanceof Cottages) {
            $status = JsonResponse::HTTP_CREATED;
            $data = [
                'id' => $cottage->getId(),
                'name' => $cottage->getName(),
                'color' => $cottage->getColor(),
                'extra_info' => $cottage->getExtraInfo()
            ];
        } else {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $data = $errorDecorator->decorateError($status);
        }


        return new JsonResponse($data, $status);
    }
    
    
    
    
        /**
     * @Route("/api/cottage/{id}")
     * @Method("PUT")
     */
    public function updateCottage(Cottages $cottage, Request $request, CottageService $cottageService, ResponseErrorDecoratorService $errorDecorator) {
   

//
        $body = $request->getContent();
        $data = json_decode($body, true);

        if (is_null($data)) {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $data = $errorDecorator->decorateError(
                JsonResponse::HTTP_BAD_REQUEST, "Invalid JSON format"
            );

            return new JsonResponse($data, $status);
        }

        $result = $cottageService->updateCottage($cottage, $data);
        if ($result instanceof Cottages) {
            $status = JsonResponse::HTTP_OK;
            $data = [
                'data' => [
                    'id' => $result->getId(),
                    'name' => $result->getName(),
                    'color' => $result->getColor(),
                    'extra_info' => $result->getExtraInfo(),
                ]
            ];
        } else {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $data = $errorDecorator->decorateError($status, $result);
        }

        return new JsonResponse($data, $status);
//        
//
    }
    
    
    
    


    /**
     * @Route("/{id}", name="cottages_delete", methods="DELETE")
     */
    public function delete(Request $request, Cottages $cottage): Response {
        if ($this->isCsrfTokenValid('delete' . $cottage->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($cottage);
            $em->flush();
        }

        return $this->redirectToRoute('cottages_index');
    }

}

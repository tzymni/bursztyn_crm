<?php

namespace App\Controller;



use App\Entity\Cottages;
use App\Service\ResponseErrorDecoratorService;
use App\Service\CottageService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class CottagesController extends Controller  {



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
    public function show(ResponseErrorDecoratorService $errorDecorator) {
//        $users = $this->getDoctrine()->getRepository(User::class)->findAllActiveWithoutPassword();
        $status = JsonResponse::HTTP_OK;


        return new JsonResponse($users, $status);
    }

    /**
     * @Route("/{id}/edit", name="cottages_edit", methods="GET|POST")
     */
    public function edit(Request $request, Cottages $cottage): Response {
        $form = $this->createForm(CottagesType::class, $cottage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cottages_edit', ['id' => $cottage->getId()]);
        }

        return $this->render('cottages/edit.html.twig', [
                    'cottage' => $cottage,
                    'form' => $form->createView(),
        ]);
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

<?php

namespace App\Controller;

use App\Entity\Cottages;
use App\Form\CottagesType;
use App\Repository\CottagesRepository;


use App\Service\ResponseErrorDecoratorService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/cottages")
 */
class CottagesController extends Controller {

    /**
     * @Route("/", name="cottages_index", methods="GET")
     */
    public function index(CottagesRepository $cottagesRepository): Response {
        return $this->render('cottages/index.html.twig', ['cottages' => $cottagesRepository->findAll()]);
    }

    /**
     * @Route("/new", name="cottages_new", methods="GET|POST")
     */
    public function add(Request $request): Response {
        $cottage = new Cottages();
        $form = $this->createForm(CottagesType::class, $cottage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($cottage);
            $em->flush();

            return $this->redirectToRoute('cottages_index');
        }

        return $this->render('cottages/new.html.twig', [
                    'cottage' => $cottage,
                    'form' => $form->createView(),
        ]);
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

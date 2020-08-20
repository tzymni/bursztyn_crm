<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\ResponseErrorDecoratorService;
use App\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController to menage API requests for User.
 *
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 * @package App\Controller
 */
class UserController extends AbstractController implements TokenAuthenticatedController
{

    /**
     * Creates new user by given data
     *
     * @Route("/user", methods={"POST"})
     * @param Request $request
     * @param UserService $userService
     * @param ResponseErrorDecoratorService $errorDecorator
     * @return JsonResponse
     */
    public function createUser(
        Request $request,
        UserService $userService,
        ResponseErrorDecoratorService $errorDecorator
    ) {
        $body = $request->getContent();
        $data = json_decode($body, true);

        $dataIsCorrect = (!is_null($data) && !empty($data['email']) && !empty($data['password'])) ? true : false;

        if (!$dataIsCorrect) {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $response = $errorDecorator->decorateError(
                $status,
                "Invalid data!"
            );
        }

        $user = $userService->createUser($data);

        if ($user instanceof User && empty($response)) {
            $status = JsonResponse::HTTP_OK;
            $response = array();
        } elseif (empty($status)) {
            $errorResponse = $user;
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $response = $errorDecorator->decorateError($status, $errorResponse);
        }

        return new JsonResponse($response, $status);
    }

    /**
     * Get active user list.
     *
     * @Route("/user/list", methods={"GET"})
     * @param ResponseErrorDecoratorService $errorDecorator
     */
    public function getUserList(
        ResponseErrorDecoratorService $errorDecorator
    ) {
        $users = $this->getDoctrine()->getRepository(User::class)->findAllActiveUsers();
        $status = JsonResponse::HTTP_OK;

        return new JsonResponse($users, $status);
    }

    /**
     * @Route("/user/{id}", methods={"GET"})
     * @param ResponseErrorDecoratorService $errorDecorator
     */
    public function getUserById(
        Request $request,
        UserService $userService,
        ResponseErrorDecoratorService $errorDecorator
    ) {
        $id = $request->get('id');

        if (empty($id) || !is_numeric($id)) {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $data = $errorDecorator->decorateError($status, "Invalid credentials");
        } else {
            $result = $userService->getActiveUserById($id);

            if ($result instanceof User) {
                $status = JsonResponse::HTTP_OK;
                $data = [
                    'id' => $result->getId(),
                    'email' => $result->getEmail(),
                    'first_name' => $result->getFirstName(),
                    'last_name' => $result->getLastName(),
                    'password' => $result->getPassword()
                ];
            } else {
                $status = JsonResponse::HTTP_BAD_REQUEST;
                $data = $errorDecorator->decorateError($status, $result);
            }
        }

        return new JsonResponse($data, $status);
    }

    /**
     * @Route("/user/{id}", methods={"DELETE"})
     * @param Request $request
     */
    public function deleteUser(
        Request $request,
        UserService $userService,
        ResponseErrorDecoratorService $errorDecorator
    ) {
        $id = $request->get('id');
        $deletedUserResponse = null;

        if (!empty($id)) {
            $user = $userService->getActiveUserById($id);

            if ($user instanceof User) {
                $deletedUserResponse = $userService->deleteUser($user);
            } else {
                $deletedUserResponse = $user;
            }
        }

        if ($deletedUserResponse instanceof User) {
            $status = JsonResponse::HTTP_OK;
            $data = array();
        } else {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $data = $errorDecorator->decorateError($status, $deletedUserResponse);
        }

        return new JsonResponse($data, $status);
    }

    /**
     * @Route("/user/{id}", methods={"PUT"})
     */
    public function updateUser(
        Request $request,
        UserService $userService,
        ResponseErrorDecoratorService $errorDecorator
    ) {
        $body = $request->getContent();
        $data = json_decode($body, true);

        if (empty($data) || empty($data['email'])) {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $response = $errorDecorator->decorateError(
                $status,
                "Invalid data!"
            );

            return new JsonResponse($response, $status);
        }

        $user = $userService->getActiveUserById($data);

        $result = $userService->updateUser($user, $data);
        if ($result instanceof User) {
            $status = JsonResponse::HTTP_OK;
            $response = array();
        } else {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $response = $errorDecorator->decorateError($status, $result);
        }

        return new JsonResponse($response, $status);
    }

}

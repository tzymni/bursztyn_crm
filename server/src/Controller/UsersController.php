<?php

namespace App\Controller;

use App\Entity\Users;
use App\Service\ResponseErrorDecoratorService;
use App\Service\UsersService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UsersController to menage API requests for Users.
 *
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 * @package App\Controller
 */
class UsersController extends AbstractController implements TokenAuthenticatedController
{

    /**
     * Creates new user by given data
     *
     * @Route("/user", methods={"POST"})
     * @param Request $request
     * @param UsersService $userService
     * @param ResponseErrorDecoratorService $errorDecorator
     * @return JsonResponse
     */
    public function createUser(
        Request $request,
        UsersService $userService,
        ResponseErrorDecoratorService $errorDecorator
    ) {
        $body = $request->getContent();
        $data = json_decode($body, true);

        $dataIsCorrect = !is_null($data) && !empty($data['email']) && !empty($data['password']);

        if (!$dataIsCorrect) {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $response = $errorDecorator->decorateError(
                $status,
                "Invalid data!"
            );
        }

        $user = $userService->createUser($data);

        if ($user instanceof Users && empty($response)) {
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
     * @return JsonResponse
     */
    public function getUserList(
        ResponseErrorDecoratorService $errorDecorator
    ) {
        try {

        $users = $this->getDoctrine()->getRepository(Users::class)->findAllActiveUsers();
        $status = JsonResponse::HTTP_OK;

        }
        catch (\Exception $exception) {
            echo $exception->getMessage();
        }
        return new JsonResponse($users, $status);
    }

    /**
     * @Route("/user/{id}", methods={"GET"})
     * @param ResponseErrorDecoratorService $errorDecorator
     * @return JsonResponse
     */
    public function getUserById(
        Request $request,
        UsersService $userService,
        ResponseErrorDecoratorService $errorDecorator
    ): JsonResponse {
        $id = $request->get('id');

        if (empty($id) || !is_numeric($id)) {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $data = $errorDecorator->decorateError($status, "Invalid credentials");
        } else {
            $result = $userService->getActiveUserById($id);

            if ($result instanceof Users) {
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
        UsersService $userService,
        ResponseErrorDecoratorService $errorDecorator
    ) {
        $id = $request->get('id');
        $deletedUserResponse = null;

        if (!empty($id)) {
            $user = $userService->getActiveUserById($id);

            if ($user instanceof Users) {
                $deletedUserResponse = $userService->deleteUser($user);
            } else {
                $deletedUserResponse = $user;
            }
        }

        if ($deletedUserResponse instanceof Users) {
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
        UsersService $userService,
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
        if ($result instanceof Users) {
            $status = JsonResponse::HTTP_OK;
            $response = array();
        } else {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $response = $errorDecorator->decorateError($status, $result);
        }

        return new JsonResponse($response, $status);
    }

}

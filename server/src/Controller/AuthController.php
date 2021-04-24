<?php

namespace App\Controller;

use App\Entity\Users;
use App\Service\AuthService;
use App\Service\ResponseErrorDecoratorService;
use App\Service\UsersService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Authentication controller.
 *
 * @package App\Controller
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 */
class AuthController extends AbstractController
{
    /**
     * Authenticate user by given credentials and response token.
     *
     * @Route("/api/authenticate", methods={"POST"})
     * @param Request $request
     * @param UsersService $userService
     * @param AuthService $authService
     * @param ResponseErrorDecoratorService $errorDecorator
     * @return JsonResponse
     */
    public function issueJWTToken(
        Request $request,
        UsersService $userService,
        AuthService $authService,
        ResponseErrorDecoratorService $errorDecorator
    ): JsonResponse {

        $email = $request->getUser();
        $plainPassword = $request->getPassword();

        if (empty($email) || empty($plainPassword)) {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $data = $errorDecorator->decorateError($status, "Invalid credentials");
        } else {

            $result = $userService->getUserByEmail($email);
            if ($result instanceof Users) {
                if (password_verify($plainPassword, $result->getPassword())) {
                    $jwt = $authService->authenticate([
                        'email' => $result->getEmail(),
                        'id' => $result->getId()
                    ]);

                    $user = array();
                    $user['id'] = $result->getId();
                    $user['email'] = $result->getEmail();
                    $user['first_name'] = $result->getFirstName();
                    $user['last_name'] = $result->getLastName();

                    $status = JsonResponse::HTTP_OK;
                    $data = [
                        'token' => $jwt,
                        'user' => $user
                    ];
                } else {
                    $status = JsonResponse::HTTP_BAD_REQUEST;
                    $data = $errorDecorator->decorateError($status, "Incorrect password");
                }
            } else {
                $status = JsonResponse::HTTP_BAD_REQUEST;
                $data = $errorDecorator->decorateError($status, $result);
            }
        }

        return new JsonResponse($data, $status);
    }
}
<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\AuthService;
use App\Service\ResponseErrorDecoratorService;
use App\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    /**
     * Authenticate user by given credentials
     *
     * @Route("/api/authenticate")
     * @Method("POST")
     * @param Request $request
     * @param UserService $userService
     * @param AuthService $authService
     * @param ResponseErrorDecoratorService $errorDecorator
     * @return JsonResponse
     */
    public function issueJWTToken(
        Request $request,
        UserService $userService,
        AuthService $authService,
        ResponseErrorDecoratorService $errorDecorator
    ) {
        $email = $request->getUser();
        $plainPassword = $request->getPassword();

        if (empty($email) || empty($plainPassword)) {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $data = $errorDecorator->decorateError($status, "Invalid credentials");
        } else {
//            $result = $this->getDoctrine()->getRepository(User::class)->getUser($email)
$result = $userService->getUser($email);


            if ($result instanceof User) {
                if (password_verify($plainPassword, $result->getPassword())) {
                    $jwt = $authService->authenticate([
                        'email' => $result->getEmail()
                    ]);
                    $status = JsonResponse::HTTP_OK;
                    $data = [
                        'token' => $jwt
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
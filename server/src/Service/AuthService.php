<?php

namespace App\Service;

use Firebase\JWT\JWT;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Service to generate/validate token authentication.
 *
 * @package App\Service
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 */
class AuthService
{

    /**
     * Algorithm to generate JWT token.
     */
    const JWT_ALGORITHM = 'HS256';

    /**
     * Validation time of the token in seconds.
     */
    const TOKEN_VALIDITY_TIME = 60 * 60 * 24;

    /**
     * @var
     */
    private $jwtSecretKey;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var ContainerBagInterface
     */
    private $containerBag;

    public function __construct(RequestStack $requestStack, $jwtSecretKey, ContainerBagInterface $containerBag)
    {
        $this->jwtSecretKey = $jwtSecretKey;
        $this->requestStack = $requestStack;
        $this->containerBag = $containerBag;
    }

    /**
     * Generate JWT token based on given user-data
     *
     * @param array $userData Array which contains relevant user-data to include into JWT payload
     * @return string JWT token required to gain access to restricted rest api methods
     */
    public function authenticate(array $userData): string
    {
        return $this->generateJWT($userData);
    }

    /**
     * Generate JWT token based on given user-data
     *
     * @param array $userData
     * @return string
     */
    private function generateJWT(array $userData): string
    {
        $currentTime = time();
        $secondsValid = self::TOKEN_VALIDITY_TIME;
        $expirationTime = $currentTime + $secondsValid;
        $payload = array(
            'sub' => $userData['id'],
            'email' => $userData['email'],
            'extra_info' => $this->containerBag->get('jst_extra_info'),
            'iat' => $currentTime,
            'exp' => $expirationTime
        );
        $key = $this->jwtSecretKey;
        $alg = self::JWT_ALGORITHM;
        return JWT::encode($payload, $key, $alg);
    }

    /**
     * Check if request is authenticated
     *
     * @return boolean true if is authenticated, false otherwise
     */
    public function isAuthenticated(): bool
    {
        $token = $this->getBearerToken();

        $decoded_array = $this->validateJWT($token);
        if (!empty($decoded_array)) {
            // process valid token
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get access token from header
     *
     * @return string authorization request header information (if exists) or null
     */
    private function getBearerToken(): ?string
    {
        $request = $this->requestStack->getCurrentRequest();
        $authHeader = $request->headers->get('Authorization');
        if (strpos($authHeader, "Bearer ") !== false) {
            $token = explode(" ", $authHeader);
            if (isset($token[1])) {
                return $token[1]; // actual token
            }
        }

        return null;
    }

    /**
     * Check if jwt token is valid
     *
     * @param string $token
     * @return array decoded array if is-valid-wt, null otherwise
     */
    private function validateJWT(string $token): ?array
    {
        try {
            $key = $this->jwtSecretKey;
            JWT::$leeway = 60; // $leeway in seconds
            $decoded = JWT::decode($token, $key, array(self::JWT_ALGORITHM));
            $decoded_array = (array)$decoded;
        } catch (\Exception $e) {
            $decoded_array = null;
        }

        return $decoded_array;
    }
}
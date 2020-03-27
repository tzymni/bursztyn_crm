<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\BaseTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserControllerTest
 * Unit tests for UserController.
 *
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 *ls
 * @package App\Tests\Controller
 */
class UserControllerTest extends BaseTestCase
{

    /**
     *  Check if getUsers method with validate tokeen response array with email, first_name, last_name keys
     *  and all assets have is_active = true.
     *
     */
    public function testGetUsers__whenValidToken_and_all_Users_are_Active()
    {
        $token = $this->getValidToken();

        $response = $this->client->get("/api/users", [
            'headers' => [
                'Authorization' => 'Bearer ' . $token
            ]
        ]);
        $responseData = json_decode($response->getBody(), true);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertArrayHasKey("email", $responseData[0]);
        $this->assertArrayHasKey("first_name", $responseData[0]);
        $this->assertArrayHasKey("last_name", $responseData[0]);

        foreach ($responseData as $data) {
            $this->assertEquals(true, $data['is_active']);
        }

    }

    /**
     * Check if http_code for invalid token equals 403.
     *
     */
    public function testGetUsers__whenTokenInvalid()
    {
        $token = sha1(time());
        $response = $this->client->get("/api/users", [
            'headers' => [
                'Authorization' => 'Bearer ' . $token
            ]
        ]);

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());

    }

}
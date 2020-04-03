<?php

namespace App\Tests\Controller;

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

    public function testGetUser__when_Id_is_Provided__Returns_User_Json_In_Response()
    {
        $token = $this->getValidToken();
        $id = $this->testUser->getId();

        $response = $this->client->get("/api/user/{$id}", [
            'headers' => [
                'Authorization' => 'Bearer ' . $token
            ]
        ]);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $responseData = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('id', $responseData);
        $this->assertArrayHasKey('email', $responseData);
        $this->assertArrayHasKey('first_name', $responseData);
        $this->assertArrayHasKey('last_name', $responseData);
        $this->assertArrayHasKey('password', $responseData);
    }

    public function testGetUser__when_Id_is_Provided_And_User_Inactive__Returns_No_Such_User_Error_Response()
    {
        $token = $this->getValidToken();
        $id = $this->testInactiveUser->getId();
        $response = $this->client->get("/api/user/{$id}", [
            'headers' => [
                'Authorization' => 'Bearer ' . $token
            ]
        ]);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());

        $responseData = json_decode($response->getBody(), true);

        $this->assertArrayHasKey("error", $responseData);
        $this->assertArrayHasKey("code", $responseData['error']);
        $this->assertArrayHasKey("message", $responseData['error']);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $responseData['error']['code']);
        $this->assertEquals("Can't find user!", $responseData['error']['message']);
    }

    public function testCreateUser__When_All_Data_Is_Provided___Returns_Success()
    {
        $emailTest = time() . '@test-create.pl';
        $data = array('email' => $emailTest, 'password' => 'test', 'first_name' => 'Test', 'last_name' => 'Test');

        $token = $this->getValidToken();
        $response = $this->client->post("/users/create", [
            'body' => json_encode($data),
            'headers' => [
                'Authorization' => 'Bearer ' . $token
            ]
        ]);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $responseData = json_decode($response->getBody(), true);
        $this->assertEmpty($responseData);
    }

    public function testCreateUser__When_Email_Already_Exist___Returns_Error_Json_Response()
    {
        $testEmail = self::TEST_INACTIVE_USER_EMAIL;
        $data = array('email' => $testEmail, 'password' => 'test', 'first_name' => 'Test', 'last_name' => 'Test');

        $token = $this->getValidToken();
        $response = $this->client->post("/users/create", [
            'body' => json_encode($data),
            'headers' => [
                'Authorization' => 'Bearer ' . $token
            ]
        ]);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $responseData = json_decode($response->getBody(), true);

        $this->assertNotEmpty($responseData);
        $this->assertEquals($responseData['error']['message'],
            sprintf('User with email %s already exist!', $testEmail));
    }

    public function testCreateUser__When_Email_Not_Provided___Returns_Error_Json_Response()
    {
        $data = array('email' => '', 'password' => 'test', 'first_name' => 'Test', 'last_name' => 'Test');

        $token = $this->getValidToken();
        $response = $this->client->post("/users/create", [
            'body' => json_encode($data),
            'headers' => [
                'Authorization' => 'Bearer ' . $token
            ]
        ]);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $responseData = json_decode($response->getBody(), true);
        $this->assertNotEmpty($responseData);
        $this->assertEquals($responseData['error']['message'], 'Invalid data!');
    }

    public function testCreateUser__When_Password_Not_Provided___Returns_Error_Json_Response()
    {
        $data = array('email' => 'test@test-create.pl', 'password' => '', 'first_name' => 'Test', 'last_name' => 'Test');

        $token = $this->getValidToken();
        $response = $this->client->post("/users/create", [
            'body' => json_encode($data),
            'headers' => [
                'Authorization' => 'Bearer ' . $token
            ]
        ]);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $responseData = json_decode($response->getBody(), true);
        $this->assertNotEmpty($responseData);
        $this->assertEquals($responseData['error']['message'], 'Invalid data!');
    }
}
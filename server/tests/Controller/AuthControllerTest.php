<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\BaseTestCase;
use Symfony\Component\HttpFoundation\Response;

class AuthControllerTest extends BaseTestCase
{
    public function testAuthenticate____When_Email_and_Password_is_Provided____Returns_JWT_Token_In_Response()
    {
        $response = $this->client->post("authenticate", [
            'auth' => [static::TEST_USER_EMAIL, static::TEST_USER_PASSWORD]
        ]);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $responseData = json_decode($response->getBody(), true);

        $this->assertArrayHasKey("token", $responseData);
    }

    public function testAuthenticate____When_User_is_Inactive___Returns_No_Such_Userr_Error_Response()
    {
        $response = $this->client->post("authenticate", [
            'auth' => [static::TEST_INACTIVE_USER_EMAIL, static::TEST_INACTIVE_USER_PASSWORD]
        ]);

        $email = self::TEST_INACTIVE_USER_EMAIL;

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $responseData = json_decode($response->getBody(), true);

        $this->assertArrayHasKey("error", $responseData);
        $this->assertArrayHasKey("code", $responseData['error']);
        $this->assertArrayHasKey("message", $responseData['error']);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $responseData['error']['code']);
        $this->assertEquals(sprintf("Can't find user with email %s", $email), $responseData['error']['message']);
    }

    public function testAuthenticate____When_Email_Does_Not_Exist____Returns_No_Such_User_Error_Response()
    {
        $email = "nosuchuser@jwtrestapi.com";
        $response = $this->client->post("authenticate", [
            'auth' => [$email, "test123"]
        ]);

        $responseData = json_decode($response->getBody(), true);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());

        $this->assertArrayHasKey("error", $responseData);
        $this->assertArrayHasKey("code", $responseData['error']);
        $this->assertArrayHasKey("message", $responseData['error']);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $responseData['error']['code']);
        $this->assertEquals(sprintf("Can't find user with email %s", $email), $responseData['error']['message']);
    }

    public function testAuthenticate____When_Password_is_Incorrect____Returns_Invalid_Password_Response()
    {
        $email = self::TEST_USER_EMAIL;

        $response = $this->client->post("authenticate", [
            'auth' => [$email, "testXXXXXXX"] // invalid password
        ]);

        $responseData = json_decode($response->getBody(), true);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());

        $this->assertArrayHasKey("error", $responseData);
        $this->assertArrayHasKey("code", $responseData['error']);
        $this->assertArrayHasKey("message", $responseData['error']);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $responseData['error']['code']);
        $this->assertEquals("Incorrect password", $responseData['error']['message']);
    }

    public function testAuthenticate____When_Incorrect_Credentials____Returns_Invalid_Credentials_Response()
    {
        $response = $this->client->post("authenticate");

        $responseData = json_decode($response->getBody(), true);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());

        $this->assertArrayHasKey("error", $responseData);
        $this->assertArrayHasKey("code", $responseData['error']);
        $this->assertArrayHasKey("message", $responseData['error']);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $responseData['error']['code']);
        $this->assertEquals("Invalid credentials", $responseData['error']['message']);
    }
}

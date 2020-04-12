<?php

namespace App\Tests\Controller;

use App\Tests\BaseTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CotagesControllerTest
 * @package App\Tests\Controller
 *
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 */
class CottageControllerTest extends BaseTestCase
{

    public function testGetActiveCottages__WhenTokenProvided__ResponseSuccess()
    {
        $token = $this->getValidToken();

        $response = $this->client->get(
            "/cottage/list",
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ]
        );
        $responseData = json_decode($response->getBody(), true);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        if (!empty($responseData)) {
            $this->assertArrayHasKey('id', current($responseData));
            $this->assertArrayHasKey('name', current($responseData));
            $this->assertArrayHasKey('color', current($responseData));
            $this->assertArrayHasKey('extra_info', current($responseData));
            $this->assertArrayHasKey('max_guests_number', current($responseData));
        }
    }

    /**
     * Check if http_code for invalid token equals 403.
     *
     */
    public function testGetActiveCottages__whenTokenInvalid__returnErrorResponse()
    {
        $token = sha1(time());
        $response = $this->client->get(
            "/cottage/list",
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ]
        );

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testCreateCottage__WhenAllDataIsProvided__ReturnsSuccess()
    {
        $data = array(
            'name' => self::testCottageName,
            'color' => '#C65244',
            'extra_info' => 'New extra info',
            'max_guests_number' => 6
        );

        $token = $this->getValidToken();
        $response = $this->client->post(
            "/cottage/add",
            [
                'body' => json_encode($data),
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ]
        );

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $responseData = json_decode($response->getBody(), true);
        $this->assertEmpty($responseData);
    }

    public function testCreateCottage__WhenValidColor__ReturnsError()
    {
        $data = array(
            'name' => self::testCottageName,
            'color' => 'RED',
            'extra_info' => 'New extra info',
            'max_guests_number' => 6
        );

        $token = $this->getValidToken();
        $response = $this->client->post(
            "/cottage/add",
            [
                'body' => json_encode($data),
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ]
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $responseData = json_decode($response->getBody(), true);

        $errorMessage = $responseData['error']['message'];

        $this->assertEquals($errorMessage, 'Invalid hex color!');
    }

}
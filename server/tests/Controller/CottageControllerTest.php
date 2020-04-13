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

    public function testGetCottageById__IdProvided__ReturnCottage()
    {
        $testCottage = $this->testCottage;

        $token = $this->getValidToken();

        $response = $this->client->get(
            "/cottage/" . $testCottage->getId(),
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ]
        );
        $responseData = json_decode($response->getBody(), true);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $this->assertArrayHasKey('id', ($responseData));
        $this->assertArrayHasKey('name', ($responseData));
        $this->assertArrayHasKey('color', ($responseData));
        $this->assertArrayHasKey('extra_info', ($responseData));
        $this->assertArrayHasKey('max_guests_number', ($responseData));
        $this->assertEquals($responseData['id'], $testCottage->getId());
    }

    public function testGetInactiveCottageById__IdProvided__ReturnError()
    {
        $testCottage = $this->testInactiveCottage;

        $token = $this->getValidToken();

        $response = $this->client->get(
            "/cottage/" . $testCottage->getId(),
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ]
        );
        $responseData = json_decode($response->getBody(), true);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertEquals($responseData['error']['message'], 'Can\'t find cottage!');
    }

    public function testUpdateCottage__WhenIdAndDataIsProvided__ReturnsSuccess()
    {
        $container = $this->getPrivateContainer();
        $cottageService = $container
            ->get('App\Service\CottageService');

        $testId = $this->testCottage->getId();
        $data = array(
            'id' => $testId,
            'name' => 'Name ' . time(),
            'color' => '#42312',
            'extra_info' => 'Extra info' . time(),
            'max_guests_number' => 2
        );

        $token = $this->getValidToken();
        $response = $this->client->put(
            "/cottage/" . $this->testCottage->getId(),
            [
                'body' => json_encode($data),
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ]
        );

        $responseData = json_decode($response->getBody(), true);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $this->assertEmpty($responseData);

        $updatedCottage = $cottageService->getActiveCottageById($testId);

        $this->assertEquals($this->testCottage->getId(), $updatedCottage->getId());
        $this->assertNotEquals($this->testCottage->getName(), $updatedCottage->getName());
        $this->assertEquals($data['max_guests_number'], $updatedCottage->getMaxGuestsNumber());
        $this->assertEquals($data['extra_info'], $updatedCottage->getExtraInfo());
        $this->assertEquals($data['color'], $updatedCottage->getColor());
    }

    public function testUpdateCottage__WhenNameAndColorNotProviden__ReturnsError()
    {
        $testId = $this->testCottage->getId();
        $data = array(
            'id' => $testId,
            'name' => '',
            'color' => '',
            'extra_info' => 'Extra info' . time(),
            'max_guests_number' => 2
        );

        $token = $this->getValidToken();
        $response = $this->client->put(
            "/cottage/" . $this->testCottage->getId(),
            [
                'body' => json_encode($data),
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ]
        );

        $responseData = json_decode($response->getBody(), true);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());

        $this->assertEquals($responseData['error']['message'], 'Invalid data!');
    }

    public function testSoftDeleteCottage__WhenIdIsProvided__ReturnsSuccess()
    {
        $container = $this->getPrivateContainer();
        $cottageService = $container
            ->get('App\Service\CottageService');

        $token = $this->getValidToken();
        $response = $this->client->delete(
            "/cottage/" . $this->testCottage->getId(),
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ]
        );

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $responseData = json_decode($response->getBody(), true);
        $this->assertEmpty($responseData);

        $deletedCottage = $cottageService->getCottageById($this->testCottage->getId());

        $this->assertFalse($deletedCottage->getIsActive());
    }

    public function testSoftDeleteCottage__WhenCottageAlreadyDeleted__ReturnsError()
    {
        $testCottageId = $this->testCottage->getId();

        $token = $this->getValidToken();
        $response = $this->client->delete(
            "/cottage/" . $testCottageId,
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ]
        );

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $responseData = json_decode($response->getBody(), true);
        $this->assertEmpty($responseData);

        // remove one more time
        $newResponse = $this->client->delete(
            "/cottage/" . $testCottageId,
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ]
        );
        $newResponseData = json_decode($newResponse->getBody(), true);

        $this->assertEquals($newResponseData['error']['message'], "Can't find cottage!");
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $newResponse->getStatusCode());
    }

}